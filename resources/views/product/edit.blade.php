@extends('layout.app')
@section('title', __('product_edit'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <form id="product" action="{{ route('product.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header d-flex align-items-center card-header-color">
                        <span class="list-title text-white">{{ __('new_product') }}</span>
                    </div>
                    <div class="card-body">
                        @include('product.form')
                    </div>
                </div>
            </form>
        </div>
        <input type="hidden" id="feature_barcodes" value="{{ feature('barcodes') }}">
    </section>
@endsection
@push('scripts')
    <script>
        $("#digital").hide();
        $("#combo-section").hide();
        $("#variant-section").hide();
        $("#diffPrice-section").hide();
        $("#promotion_price").hide();

        $('select[name="type"]').on("change", function() {
            const value = $(this).val();
            if (value == 'Combo') {
                $("#combo-section").show();
                $("#batch-option").hide();
                $("#variant-option").hide();
            } else {
                $("#combo-section").hide();
                $("#batch-option").show();
                $("#variant-option").show();
            }
        });
    </script>
    @if ($product->is_variant == 1)
        <script>
            $("#variant-section").show();
        </script>
    @endif

    @if ($product->is_promotion_price == 1)
        <script>
            $("#promotion_price").show();
        </script>
    @endif

    @if ($product->type->value == 'Combo')
        <script>
            $("#batch-option").hide();
            $("#variant-option").hide();
        </script>
    @endif

    <script>
        $('select[name="unit_id"]').on("change", function() {
            var unitID = $(this).val();
            if (unitID) {
                $.ajax({
                    url: "/products/saleunit",
                    type: "GET",
                    data: {
                        id: unitID,
                    },
                    dataType: "json",
                    success: function(res) {
                        $('select[name="sale_unit_id"]').empty();
                        $('select[name="purchase_unit_id"]').empty();
                        $.each(res.data.unit, function(key, value) {
                            $('select[name="sale_unit_id"]').append(
                                '<option value="' + key + '">' + value + "</option>"
                            );
                            $('select[name="purchase_unit_id"]').append(
                                '<option value="' + key + '">' + value + "</option>"
                            );
                        });
                    },
                });
            } else {
                $('select[name="sale_unit_id"]').empty();
                $('select[name="purchase_unit_id"]').empty();
            }
        });
        $("#generate-code").on("click", function() {
            $.ajax({
                url: "/product/gencode",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    $('input[name="code"]').val(res);
                },
            });
        });
    </script>
    <script src="{{ asset('assets/product/create.js') }}"></script>
@endpush
