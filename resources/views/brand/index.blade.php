@extends('layout.app')
@section('title', __('brands'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('brands') }}</span>
                    <div>
                        <a href="{{ route('brand.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        <button class="btn common-btn" data-toggle="modal" data-target="#createModal"><i
                                class="fa fa-plus"></i>&nbsp;&nbsp;
                            {{ __('add_brand') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('image') }}</th>
                                    <th>{{ __('brand') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $key => $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td> <img src="{{ $brand->thumbnail->file }}" height="30" width="30"></td>
                                        <td>{{ $brand->title }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-toggle="modal"
                                                data-target="#editModal_{{ $brand->id }}"
                                                class="btn btn-sm common-btn edit-btn"><i class="fa fa-edit"></i></a>
                                            <a id="delete" href="{{ route('brand.delete', $brand->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                            <!-- Edit Modal -->
                                            <div id="editModal_{{ $brand->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static" aria-labelledby="BrandModalLabel" aria-hidden="true"
                                                class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('brand.update', $brand->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-header card-header-color">
                                                                <span id="BrandModalLabel"
                                                                    class="modal-title list-title text-white">{{ __('edit_brand') }}</span>
                                                                <button type="button" data-dismiss="modal"
                                                                    aria-label="Close" class="close"><span
                                                                        aria-hidden="true"><i
                                                                            class="fa fa-times text-white"></i></span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <x-inputGroup name="title"
                                                                            title="{{ __('title') }}" type="text"
                                                                            :required="true"
                                                                            value="{{ $brand->title ?? old('title') }}"
                                                                            placeholder="{{ __('enter_your_brand_title') }}" />
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <x-fileInputGroup name="image"
                                                                            title="{{ __('image') }}"
                                                                            :required="false" />
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
        </div>
    </section>
    <!-- Create Modal -->
    <div id="createModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="exampleModalLabel" class="modal-title list-title text-white">{{ __('new_brand') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="title" title="{{ __('title') }}" type="text" :required="true"
                                    value="{{ old('title') }}" placeholder="{{ __('enter_your_brand_title') }}" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-fileInputGroup name="image" title="{{ __('image') }}" :required="true" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit"class="btn common-btn">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
