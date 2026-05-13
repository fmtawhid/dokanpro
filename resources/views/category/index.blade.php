@extends('layout.app')
@section('title', __('categories'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="list-title">{{ __('categories') }}</span>
                <div>
                    <button type="button" class="btn print-btn" onclick="printCategory()"><i
                            class="fa fa-print"></i>&nbsp;&nbsp;{{ __('print') }}</button>
                    <button type="button" class="btn import-btn" data-toggle="modal" data-target="#createImportModal"><i
                            class="fa fa-file-import"></i>&nbsp;&nbsp;{{ __('import') }}</button>
                    <a href="{{ route('category.trash') }}" class="btn btn-warning"><i
                            class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                    <button type="button" class="btn common-btn" data-toggle="modal" data-target="#createModal"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('add_category') }}</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover dataTable" style="width: 100%">
                        <thead class="table-bg-color">
                            <tr>
                                <th>{{ __('sl') }}</th>
                                <th>{{ __('image') }}</th>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('parent') }}</th>
                                <th>{{ __('number_of_product') }}</th>
                                <th class="not-exported">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ $category->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                            height="30" width="30"></td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parentCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $category->product->count() ?? 0 }}</td>
                                    <td>
                                        <a class="btn btn-sm common-btn" data-toggle="modal"
                                            data-target="#editModal_{{ $category->id }}" href="javascript:void(0)"><i
                                                class="fa fa-edit"></i></a>
                                        <a id="delete" href="{{ route('category.delete', $category->id) }}"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        <!-- Edit Modal -->
                                        <div id="editModal_{{ $category->id }}" tabindex="-1" role="dialog"
                                            data-backdrop="static" aria-labelledby="categoryEditModalLabel"
                                            aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('category.update', $category->id) }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-header card-header-color">
                                                            <span id="categoryEditModalLabel"
                                                                class="modal-title list-title text-white">{{ __('edit_category') }}</span>
                                                            <button type="button" data-dismiss="modal" aria-label="Close"
                                                                class="close" onclick="modalClose()"><span
                                                                    aria-hidden="true"><i
                                                                        class="fa fa-times text-white"></i></span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-2">
                                                                <div class="col-md-12 mb-3">
                                                                    <x-inputGroup name="name"
                                                                        title="{{ __('name') }}" type="text"
                                                                        :required="true"
                                                                        value="{{ $category->name ?? old('name') }}"
                                                                        placeholder="{{ __('enter_your_category_name') }}" />
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <x-select name="parent_id"
                                                                        title="{{ __('parent_category') }}"
                                                                        placeholder="{{ __('select_a_option') }}"
                                                                        :required="false">
                                                                        @foreach ($parentCategories as $parentCategory)
                                                                            <option value="{{ $parentCategory->id }}"
                                                                                {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                                                                {{ $parentCategory->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </x-select>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <x-fileInputGroup name="image"
                                                                        title="{{ __('image') }}" :required="true" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ __('close') }}</button>
                                                            <button type="submit"
                                                                class="btn common-btn">{{ __('update_and_save') }}</button>
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
    <div id="createModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="categoryCreateModal"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="categoryCreateModal"
                            class="modal-title list-title text-white">{{ __('new_category') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="name" title="{{ __('name') }}" type="text"
                                    :required="true" value="{{ old('name') }}"
                                    placeholder="{{ __('enter_your_category_name') }}" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-select name="parent_id" title="{{ __('parent_category') }}"
                                    placeholder="{{ __('select_a_option') }}" :required="false">
                                    @foreach ($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}"
                                            {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-fileInputGroup name="image" title="{{ __('image') }}" :required="true" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- import modal -->
    <div id="createImportModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="categoryEditModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('category.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="categoryEditModalLabel"
                            class="modal-title list-title text-white">{{ __('import_category') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <x-fileInputGroup name="file" title="{{ __('import') }}" :required="true" />
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('download.category.sample') }}"
                                    class="btn common-btn w-100">{{ __('download') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function printCategory() {
            const length = $('select[name="dataTable_length"]').val();
            window.location.href = "{{ route('category.print') }}" + "?length=" + length
        }
    </script>
@endpush
