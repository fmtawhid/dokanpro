@extends('layout.app')
@section('title', __('shop_categories'))
@section('content')
    <style>
        .business-module-btn {
            background-color: #0bd577;
            border: 1px solid #0bd577;
        }
    </style>
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <x-section-header title="shop_categories" />
                <x-create-button name="add_shop_category" target="createShopCategoryModal" />
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('primary_color') }}</th>
                                <th>{{ __('secondary_color') }}</th>
                                <th>{{ __('description') }}</th>
                                <th>{{ __('status') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shopCategories as $shopCategory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $shopCategory->name }}</td>
                                    <td>
                                        <span class="primary_color"
                                            style="background: {{ $shopCategory->primary_color }}">
                                        </span>
                                    </td>
                                    <td>
                                        <span class="primary_color"
                                            style="background: {{ $shopCategory->secondary_color }}">
                                        </span>
                                    </td>
                                    <td>{{ $shopCategory->description ?? 'N/A' }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="subscriptionStatus d-none"
                                                data-id="{{ $shopCategory->id }}"
                                                {{ $shopCategory->status->value == 'Active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success business-module-btn" data-toggle="modal"
                                            data-target="#shopCategoryBusinessModuleModal_{{ $shopCategory->id }}">
                                            <i class="fa fa-thumbtack"></i>
                                        </button>
                                        <x-edit-button target="shopCategoryEditModal_{{ $shopCategory->id }}" />
                                        <x-delete-button route="{{ route('shop.category.delete', $shopCategory->id) }}" />
                                        <!-- Edit Modal -->
                                        <div id="shopCategoryEditModal_{{ $shopCategory->id }}" tabindex="-1"
                                            role="dialog" data-backdrop="static"
                                            aria-labelledby="shopCategoryEditModalLabel_{{ $shopCategory->id }}""
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('shop.category.update', $shopCategory->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <x-modal-header header="edit_shop_category"
                                                            id="shopCategoryEditModalLabel_{{ $shopCategory->id }}" />
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <x-inputGroup name="name" title="name"
                                                                        type="text" :required="true"
                                                                        value="{{ $shopCategory->name }}"
                                                                        placeholder="enter_your_shop_category_name" />
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <x-select name="status" title="status"
                                                                        placeholder="select_a_option">
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status->value }}"
                                                                                {{ $status->value == $shopCategory->status->value ? 'selected' : '' }}>
                                                                                {{ $status->value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </x-select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <x-inputGroup type="color" name="primary_color"
                                                                        title="primary_color"
                                                                        value="{{ $shopCategory->primary_color }}"
                                                                        :required="true" placeholder="primary_color" />
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <x-inputGroup type="color" name="secondary_color"
                                                                        title="secondary_color"
                                                                        value="{{ $shopCategory->secondary_color }}"
                                                                        :required="true" placeholder="secondary_color" />
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <x-textarea-group name="description" title="description"
                                                                        :required="false"
                                                                        value="{{ $shopCategory->description }}"
                                                                        placeholder="enter_your_shop_category_description" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <x-modal-close-button />
                                                            <x-common-button name="update_and_save" />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Permission Modal -->
                                        <div id="shopCategoryBusinessModuleModal_{{ $shopCategory->id }}" tabindex="-1"
                                            role="dialog" data-backdrop="static"
                                            aria-labelledby="shopCategoryBusinessModuleModalLabel_{{ $shopCategory->id }}""
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered modal-xl">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('shop.category.business.module.update', $shopCategory->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <x-modal-header header="business_modules_update"
                                                            id="shopCategoryBusinessModuleModalLabel_{{ $shopCategory->id }}" />
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                @foreach ($businessModules as $businessModule)
                                                                    @php
                                                                        $checked = in_array(
                                                                            $businessModule->id,
                                                                            $shopCategory->businessModules
                                                                                ->pluck('id')
                                                                                ->toArray(),
                                                                        )
                                                                            ? 'checked'
                                                                            : '';
                                                                    @endphp
                                                                    <div class="col-md-4">
                                                                        <div class="icheckbox_square-blue checked m-3">
                                                                            <div class="checkbox" style="font-size: 13px;">
                                                                                <input type="checkbox"
                                                                                    id="{{ $businessModule->name }}"
                                                                                    name="business_modules[]"
                                                                                    style="transform: scale(1.5);"
                                                                                    value="{{ $businessModule->id }}"
                                                                                    {{ $checked }} />
                                                                                <label for="{{ $businessModule->name }}"
                                                                                    style="margin-left: 10px; font-size: 15px;">
                                                                                    {{ $businessModule->label }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <x-modal-close-button />
                                                            <x-common-button name="update_and_save" />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Create Modal -->
    <div id="createShopCategoryModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="createShopCategoryModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('shop.category.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_shop_category" id="createShopCategoryModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-inputGroup name="name" title="name" type="text" :required="true"
                                    value="" placeholder="enter_your_shop_category_name" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select name="status" title="status" placeholder="select_a_option">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status') == $status->value ? 'selected' : '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup type="color" name="primary_color" title="primary_color" value=""
                                    :required="true" placeholder="primary_color" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-inputGroup type="color" name="secondary_color" title="secondary_color"
                                    value="" :required="true" placeholder="secondary_color" />
                            </div>
                            <div class="col-md-12">
                                <x-textarea-group name="description" title="description" :required="false" value=""
                                    placeholder="enter_your_shop_category_description" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-modal-close-button />
                        <x-common-button name="submit" />>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.shopCategoryDeleteBtn', function(e) {
            const route = $(this).attr('data-action');
            confirmDelete(route)
        });
        $('.subscriptionStatus').on("change", function() {
            const id = $(this).attr('data-id')
            const url = "{{ url('shop-category/status-chanage/') }}";
            if ($(this).is(":checked")) {
                window.location.href = url + '/' + id + '/Active';
            } else {
                window.location.href = url + '/' + id + '/Inactive';
            }
        });
    </script>
@endpush
