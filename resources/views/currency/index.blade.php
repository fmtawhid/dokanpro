@extends('layout.app')
@section('title', __('currencies'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="currencies" />
                    <x-create-button name="add_currency" target="createCurrencyModal" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('symbol') }}</th>
                                    <th>{{ __('code') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currencies as $currency)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->symbol }}</td>
                                        <td>{{ $currency->code }}</td>
                                        <td>
                                            <x-edit-button target="editCurrencyModal_{{ $currency->id }}" />
                                            <x-delete-button route="{{ route('currency.delete', $currency->id) }}" />
                                        </td>
                                    </tr>
                                    <div id="editCurrencyModal_{{ $currency->id }}" tabindex="-1" role="dialog"
                                        data-backdrop="static" aria-labelledby="editCurrencyModalLabel_{{ $currency->id }}" aria-hidden="true"
                                        class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('currency.update', $currency->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <x-modal-header header="edit_currency" id="editCurrencyModalLabel_{{ $currency->id }}" />
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup type="text" name="name"
                                                                    title="nane"
                                                                    value="{{ $currency->name }}" :required="true"
                                                                    placeholder="enter_your_currency_name" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup type="text" name="symbol"
                                                                    title="symbol"
                                                                    value="{{ $currency->symbol }}" :required="true"
                                                                    placeholder="enter_your_currency_symbol" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <x-inputGroup type="text" name="code"
                                                                    title="code"
                                                                    value="{{ $currency->code }}" :required="true"
                                                                    placeholder="enter_your_currency_code" />
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

    <div id="createCurrencyModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createCurrencyModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('currency.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_currency" id="createCurrencyModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="text" name="name" title="name" value=""
                                    :required="true" placeholder="enter_your_currency_name" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="text" name="symbol" title="symbol" value=""
                                    :required="true" placeholder="enter_your_currency_symbol" />
                            </div>
                            <div class="col-md-12">
                                <x-inputGroup type="text" name="code" title="code" value=""
                                    :required="true" placeholder="enter_your_currency_code" />
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
