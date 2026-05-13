@extends('layout.app')
@section('title', __('suppliers'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="suppliers" class="text-white" />
                    <div>
                        <a href="{{ route('supplier.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        <button type="button" class="btn import-btn" data-toggle="modal" data-target="#createImportModal"><i
                                class="fa fa-file-import"></i>&nbsp;&nbsp;{{ __('import') }}</button>
                        @can('supplier.create')
                            <a href="{{ route('supplier.create') }}" class="btn common-btn"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;
                                {{ __('add_aupplier') }}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('image') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('company_name') }}</th>
                                    <th>{{ __('tax_number') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('phone_number') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $supplier->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                                height="30" width="30">
                                        </td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->company_name }}</td>
                                        <td>{{ $supplier->vat_number ?? 'N/A' }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->phone_number }}</td>
                                        <td>
                                            <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                class="btn btn-sm common-btn"><i class="fa fa-edit"></i></a>

                                            <a id="delete" href="{{ route('supplier.destroy', $supplier->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- import modal -->
        <div id="createImportModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="categoryEditModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('supplier.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header card-header-color">
                            <span id="categoryEditModalLabel"
                                class="modal-title list-title text-white">{{ __('import_supplier') }}</span>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"
                                onclick="modalClose()"><span aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-12 mb-3">
                                    <x-fileInputGroup name="file" title="{{ __('import') }}" :required="true"
                                        placeholder="{{ __('enter_your_supplier_name') }}" />
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('download.supplier.sample') }}"
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
    </section>
@endsection
