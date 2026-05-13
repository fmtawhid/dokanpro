@extends('layout.app')
@section('title', __('products'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('products') }}</span>
                    <div class="d-flex">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown">
                                {{ request()->status ?? __('select_a_option') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('product.index') }}">{{ __('all_produts') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('product.index', ['status' => 'standard']) }}">{{ __('standard') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('product.index', ['status' => 'combo']) }}">{{ __('combo') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('product.index', ['status' => 'digital']) }}">{{ __('digital') }}</a>
                            </div>

                        </div>
                        <button type="button" class="btn import-btn" data-toggle="modal"
                            data-target="#createImportModal"><i
                                class="fa fa-file-import"></i>&nbsp;&nbsp;{{ __('import') }}</button>
                        <a href="{{ route('product.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        @can('product.create')
                            <a href="{{ route('product.create') }}" class="btn common-btn"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_product') }}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th>{{ __('sl') }}</th>
                                    <th>{{ __('image') }}</th>
                                    <th width="300px">{{ __('name') }}</th>
                                    @if (feature('barcodes'))
                                        <th>{{ __('code') }}</th>
                                    @endif
                                    <th>{{ __('brand') }}</th>
                                    <th>{{ __('category') }}</th>
                                    @if (feature('purchases'))
                                        <th>{{ __('quantity') }}</th>
                                    @endif
                                    <th>{{ __('unit') }}</th>
                                    <th>{{ __('price') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ $product->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                                height="30" width="30"></td>
                                        <td>{{ $product->name }}</td>
                                        @if (feature('barcodes'))
                                            <td>{{ $product->code }}</td>
                                        @endif
                                        <td>{{ $product->brand?->title }}</td>
                                        <td>{{ $product->category?->name ?? 'N/A' }}</td>
                                        @if (feature('purchases'))
                                            <td>{{ $product->qty }}</td>
                                        @endif
                                        <td>
                                            {{ ucfirst($product->unit?->name) ?? 'N/A' }}
                                        </td>
                                        <td>{{ numberFormat($product->price) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a data-toggle="modal"
                                                        data-target="#productDetails_{{ $product->id }}"
                                                        class="dropdown-item" href="#"><i
                                                            class="fa fa-eye text-info"></i>&nbsp;&nbsp;{{ __('view') }}</a>
                                                    @can('product.edit')
                                                        <a href="{{ route('product.edit', $product->id) }}"
                                                            class="dropdown-item"><i
                                                                class="fa fa-edit text-primary"></i>&nbsp;&nbsp;
                                                            {{ __('edit') }}</a>
                                                    @endcan
                                                    @can('product.destroy')
                                                        <a id="delete" href="{{ route('product.destroy', $product->id) }}"
                                                            class="dropdown-item"><i
                                                                class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                                                            {{ __('delete') }}</a>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Product Details Show Modal -->

                                    <div id="productDetails_{{ $product->id }}" data-backdrop="static" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidiven="true"
                                        class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header card-header-color">
                                                    <h5 id="exampleModalLabel" class="modal-title">
                                                        {{ __('product_details') }}</h5>
                                                    <button type="button" id="close-btn" data-dismiss="modal"
                                                        aria-label="Close" class="close"><span aria-hidiven="true"><i
                                                                class="fa fa-times text-white"></i></span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-5" id="slider-content">
                                                            <img src="{{ $product->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                                                width="100%">
                                                        </div>
                                                        <div class="col-md-6" id="product-content">
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('product_name') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">{{ $product->name }}</div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('product_type') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">
                                                                    {{ ucfirst($product->type->value) }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('product_code') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">{{ $product->code }}</div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('brand') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">
                                                                    {{ $product->brand->title ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('category') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">
                                                                    {{ $product->category->name ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('unit') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">
                                                                    {{ ucfirst($product->unit?->name) ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                            @if (feature('purchases'))
                                                                <div class="row mb-2">
                                                                    <div class="col-sm-5">
                                                                        <b>{{ __('quantity') }}</b>
                                                                    </div>
                                                                    <div class="col-sm-1">:</div>
                                                                    <div class="col-sm-6">
                                                                        {{ $product->qty ?? 0 }}</div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-sm-5">
                                                                        <b>{{ __('alert_quantity') }}</b>
                                                                    </div>
                                                                    <div class="col-sm-1">:</div>
                                                                    <div class="col-sm-6">
                                                                        {{ $product->alert_quantity ?? 0 }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('purchase_cost') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">{{ numberFormat($product->cost) }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-sm-5">
                                                                    <b>{{ __('selling_price') }}</b>
                                                                </div>
                                                                <div class="col-sm-1">:</div>
                                                                <div class="col-sm-6">{{ numberFormat($product->price) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <label><strong>{{ __('product_details') }}</strong></label>
                                                            <p>{!! $product->product_details !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- import modal -->
    <div id="createImportModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="categoryEditModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('product.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="categoryEditModalLabel"
                            class="modal-title list-title text-white">{{ __('import_product') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"
                            onclick="modalClose()"><span aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('import') }}<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('download.product.sample') }}"
                                    class="btn common-btn w-100">{{ __('download') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="modalClose()">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.productDetailsViewBtn', function(e) {
            const id = $(this).attr('data-id')
            $('#productDetailsShowModal_' + id).modal('show')
        });
        $(document).on('click', '.productDeleteBtn', function(e) {
            const route = $(this).attr('data-action');
            confirmDelete(route)
        });

        $('select[name="type"]').on('change', function() {
            var type = $(this).val();
            $('#typeValue').val(type);
            $('#productType').submit();

        });

        function printCategory() {
            const length = $('select[name="dataTable_length"]').val();
            window.location.href = "{{ route('product.print') }}" + "?length=" + length
        }
    </script>
@endpush
