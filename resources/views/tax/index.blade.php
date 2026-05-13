@extends('layout.app')
@section('title', __('taxs'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="taxs" />
                    <x-create-button target="createTaxModal" name="add_tax" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('rate') }} (%)</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taxs as $tax)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tax->name }}</td>
                                        <td>{{ $tax->rate }}</td>
                                        <td>
                                            <x-edit-button target="editTaxModal_{{ $tax->id }}" />
                                            <x-delete-button route="{{ route('tax.delete', $tax->id) }}" />
                                        </td>
                                    </tr>
                                    <div id="editTaxModal_{{ $tax->id }}" data-backdrop="static" tabindex="-1"
                                        role="dialog" aria-labelledby="editTaxModalLabel_{{ $tax->id }}"
                                        aria-hidden="true" class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('tax.update', $tax->id) }}" method="post">
                                                    @csrf
                                                    <x-modal-header header="edit_tax"
                                                        id="editTaxModalLabel_{{ $tax->id }}" />
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup type="text" name="name" title="name"
                                                                    value="{{ $tax->name }}" :required="true"
                                                                    placeholder="enter_your_tax_name" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <x-inputGroup type="number" name="rate" title="rate"
                                                                    value="{{ $tax->rate }}" :required="true"
                                                                    placeholder="enter_your_tax_rate" />
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="createTaxModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createTaxModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('tax.store') }}" method="post">
                    @csrf
                    <x-modal-header header="new_tax" id="createTaxModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="text" name="name" title="name" value="" :required="true"
                                    placeholder="enter_your_tax_name" />
                            </div>
                            <div class="col-md-12">
                                <x-inputGroup type="number" name="rate" title="rate" value="" :required="true"
                                    placeholder="enter_your_tax_rate" />
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
