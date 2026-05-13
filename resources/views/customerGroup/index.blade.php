@extends('layout.app')
@section('title', __('customer_groups'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="customer_groups" />
                    <div>
                        <a href="{{ route('customer.group.trash') }}" class="btn btn-warning"><i
                                class="fa fa-trash"></i>&nbsp;&nbsp;{{ __('trash') }}</a>
                        <x-create-button name="add_customer_group" target="createCustomerGroupModal" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('percentage') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerGroups as $customer_group)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customer_group->name }}</td>
                                        <td>{{ $customer_group->percentage }} (%)</td>
                                        <td>
                                            <x-edit-button target="editCustomerGroupModal_{{ $customer_group->id }}" />
                                            <x-delete-button
                                                route="{{ route('customer.group.delete', $customer_group->id) }}" />
                                            <!-- edit modal -->
                                            <div id="editCustomerGroupModal_{{ $customer_group->id }}" tabindex="-1"
                                                role="dialog" data-backdrop="static"
                                                aria-labelledby="editCustomerGroupModalLabel_{{ $customer_group->id }}"
                                                aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('customer.group.update', $customer_group->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <x-modal-header header="edit_customer_group"
                                                                id="editCustomerGroupModalLabel_{{ $customer_group->id }}" />
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <x-inputGroup name="name" title="name"
                                                                            type="text" :required="true"
                                                                            value="{{ $customer_group->name }}"
                                                                            placeholder="enter_your_customer_group_name" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <x-inputGroup name="percentage"
                                                                            title="discount_percentage" type="number"
                                                                            :required="true"
                                                                            value="{{ $customer_group->percentage }}"
                                                                            placeholder="enter_your_discount_percentage" />
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
    <!-- Ceate modal -->
    <div id="createCustomerGroupModal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="createCustomerGroupModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('customer.group.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_customer_group" id="createCustomerGroupModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="name" title="name" type="text" :required="true" value=""
                                    placeholder="enter_your_customer_group_name" />
                            </div>
                            <div class="col-md-12">
                                <x-inputGroup name="percentage" title="discount_percentage" type="number" :required="true"
                                    value="" placeholder="enter_your_discount_percentage" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-modal-close-button />
                        <x-common-button name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
