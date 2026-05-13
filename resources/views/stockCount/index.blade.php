@extends('layout.app')
@section('title', __('stock_count'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('stock_count') }}</span>
                    <button class="btn common-btn" data-toggle="modal" data-target="#createModal"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;
                        {{ __('add_stock') }}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('warehouse') }}</th>
                                    <th>{{ __('category') }}</th>
                                    <th>{{ __('brand') }}</th>
                                    <th>{{ __('type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockCounts as $stock_count)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateFormat($stock_count->created_at) }}
                                        </td>
                                        <td>{{ $stock_count->reference_no }}</td>
                                        <td>{{ $stock_count->warehouse->name }}</td>
                                        <td>
                                            @if ($stock_count->category_id)
                                                @php
                                                    $categoriesName = \App\Repositories\CategoryRepository::query()
                                                        ->whereIn('id', json_decode($stock_count->category_id))
                                                        ->get();
                                                @endphp
                                                @foreach ($categoriesName as $catKey => $category)
                                                    {{ $catKey ? ', ' . $category->name : $category->name }}
                                                @endforeach
                                            @else
                                                N\A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($stock_count->brand_id)
                                                @php
                                                    $brandsName = \App\Repositories\BrandRepository::query()
                                                        ->whereIn('id', json_decode($stock_count->brand_id))
                                                        ->get();
                                                @endphp
                                                @foreach ($brandsName as $brandKey => $brand)
                                                    {{ $brandKey ? ', ' . $brand->title : $brand->title }}
                                                @endforeach
                                            @else
                                                N\A
                                            @endif
                                        </td>
                                        @if ($stock_count->type == 'full')
                                            <td>
                                                <div class="badge badge-primary">{{ __('full') }}</div>
                                            </td>
                                        @else
                                            <td>
                                                <div class="badge badge-info">{{ __('partial') }}</div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="createModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('stock.count.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="exampleModalLabel"
                            class="list-title modal-title text-white">{{ __('new_stock') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 form-group">
                                <label class="mb-2">{{ __('warehouse') }}<span class="text-danger">*</span></label>
                                <select required name="warehouse_id" id="warehouse_id" class="form-control mb-2">
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="mb-2">{{ __('type') }}<span class="text-danger">*</span></label>
                                <select class="form-control mb-2" name="type">
                                    <option value="full">{{ __('full') }}</option>
                                    <option value="partial">{{ __('partial') }}</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group" id="category">
                                <label class="mb-2">{{ __('category') }}</label>
                                <select name="category_id[]" id="category_id" class="form-control mb-2" multiple="multiple"
                                    style="width: 100%">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group" id="brand">
                                <label class="mb-2">{{ __('brand') }}</label>
                                <select name="brand_id[]" id="brand_id" class="form-control mb-2" multiple="multiple"
                                    style="width: 100%">
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#brand_id').select2();
        });
        $(document).ready(function() {
            $('#category_id').select2();
        });

        $("#category, #brand").hide();

        $('select[name=type]').on('change', function() {
            if ($(this).val() == 'partial')
                $("#category, #brand").show(500);
            else
                $("#category, #brand").hide(500);
        });
    </script>
@endpush
