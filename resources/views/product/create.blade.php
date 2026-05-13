@extends('layout.app')
@section('title', __('new_product'))
@section('content')
    <style>
        .jpreview-image {
            width: 308px;
            height: 250px;
            margin: 10px;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

        }
    </style>
    <section class="forms">
        <div class="container-fluid">
            <form id="product" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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

    <style>
        .product-item {
            background: #cccccc1c;
            border-radius: 8px;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .products {
            background-color: #eef1f5 !important;
            max-height: 400px;
            z-index: 999;
            top: 40px;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            display: none;
            overflow-x: hidden;
            overflow-y: scroll;
        }
    </style>
@endsection
@push('scripts')
    <script>
        $("#digital").hide();
        $("#combo-section").hide();
        $("#variant-section").hide();
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
