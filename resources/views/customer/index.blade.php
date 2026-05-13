@extends('layout.app')
@section('title', __('customers'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="customers" />
                    <div>
                        <a href="{{ route('customer.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        <button type="button" class="btn import-btn" data-toggle="modal" data-target="#customerImportModal"><i
                                class="fa fa-file-import"></i>&nbsp;&nbsp;{{ __('import') }}</button>
                        @can('customer.create')
                            <a href="{{ route('customer.create') }}" class="btn common-btn"><i class="fa fa-plus"></i>
                                {{ __('add_customer') }}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('customer_group') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('company_name') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('phone_number') }}</th>
                                    <th>{{ __('tax_number') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customer->customerGroup->name ?? 'N/A' }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->company_name ?? 'N/A' }}</td>
                                        <td>{{ $customer->email ?? 'N/A' }}</td>
                                        <td>{{ $customer->phone_number }}</td>
                                        <td>{{ $customer->tax_no ?? 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn common-btn py-0 px-1" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    @can('customer.edit')
                                                        <a href="{{ route('customer.edit', $customer->id) }}"
                                                            class="dropdown-item"><i class="fa fa-edit text-primary"></i>
                                                            {{ __('edit') }}</a>
                                                    @endcan
                                                    @can('customer.destroy')
                                                        <a id="delete" href="{{ route('customer.destroy', $customer->id) }}"
                                                            class="dropdown-item"><i class="fa fa-trash text-danger"></i>
                                                            {{ __('delete') }}</a>
                                                    @endcan
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
    <!-- import modal -->
    <div id="customerImportModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="customerImportModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('customer.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <x-modal-header header="import_customer" id="customerImportModalLabel" />
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="file" title="import" type="file" :required="true"
                                    placeholder="enter_your_customer_name" value="" />
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('download.customer.sample') }}"
                                    class="btn common-btn w-100">{{ __('download') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-modal-close-button />
                        <x-common-button name="import" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
