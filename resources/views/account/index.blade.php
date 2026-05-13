@extends('layout.app')
@section('title', __('accounts'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('accounts') }}</span>
                    <button class="btn common-btn" data-toggle="modal" data-target="#account-modal"><i class="fa fa-plus"
                            aria-hidden="true"></i>
                        {{ __('add_account') }}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('account_no') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('balance') }}</th>
                                    <th>{{ __('note') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $account->account_no }}</td>
                                        <td>{{ $account->name }}</td>
                                        @if ($account->total_balance)
                                            <td>{{ numberFormat($account->total_balance ?? 0.0) }}</td>
                                        @endif
                                        <td>{{ $account->note ?? 'N/A' }}</td>
                                        <td>
                                            <a data-toggle="modal" data-target="#addMoney_{{ $account->id }}"
                                                href="javascript:void(0)" class="btn btn-sm common-btn mb-2"><i
                                                    class="fa fa-plus-circle"></i></a>

                                            <a data-toggle="modal" data-target="#editModal_{{ $account->id }}"
                                                href="javascript:void(0)" class="edit-btn btn btn-sm common-btn mb-2">
                                                <i class="fa fa-edit"></i></a>

                                            <a id="delete" href="{{ route('account.destroy', $account->id) }}"
                                                class="btn btn-sm btn-danger mb-2"><i class="fa fa-trash"></i></a>

                                        </td>
                                    </tr>
                                    <!-- add money modal -->
                                    <div id="addMoney_{{ $account->id }}" tabindex="-1" role="dialog"
                                        data-backdrop="static" aria-labelledby="accountModal" aria-hidden="true"
                                        class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('account.update.balance', $account->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header card-header-color">
                                                        <span id="accountModal" class="modal-title list-title text-white">
                                                            {{ __('deposit_balance') }}</span>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-12 mb-3">
                                                                <x-input name=""
                                                                    title="{{ __('available_balance') }}" type="text"
                                                                    :required="true" :readonly="true"
                                                                    value="{{ numberFormat($account->total_balance) }}"
                                                                    placeholder="{{ __('enter_your_available_balance') }}" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="total_balance"
                                                                    title="{{ __('deposit_amount') }}" type="number"
                                                                    value="{{ old('total_balance') }}" :required="true"
                                                                    placeholder="{{ __('enter_your_deposit_amount') }}" />
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
                                    <!-- account edit modal -->
                                    <div id="editModal_{{ $account->id }}" tabindex="-1" role="dialog"
                                        data-backdrop="static" aria-labelledby="accountModal" aria-hidden="true"
                                        class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('account.update', $account->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header card-header-color">
                                                        <span id="accountModal"
                                                            class="modal-title list-title text-white">{{ __('edit_account_info') }}</span>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="name" title="{{ __('name') }}"
                                                                    type="text" :required="true"
                                                                    value="{{ $account->name ?? old('name') }}"
                                                                    placeholder="{{ __('enter_your_account_name') }}" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="account_no"
                                                                    title="{{ __('account_no') }}" type="number"
                                                                    :required="true"
                                                                    value="{{ $account->account_no ?? old('account_no') }}"
                                                                    placeholder="{{ __('enter_your_account_no') }}" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="balance" title="{{ __('balance') }}"
                                                                    type="number" :required="true"
                                                                    value="{{ $account->total_balance ?? old('balance') }}"
                                                                    placeholder="{{ __('enter_your_current_balance') }}" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="note" title="{{ __('note') }}"
                                                                    type="text" :required="false"
                                                                    value="{{ $account->note ?? old('note') }}"
                                                                    placeholder="{{ __('enter_your_note') }}" />
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account modal -->
    <div id="account-modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('account.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="exampleModalLabel"
                            class="modal-title list-title text-white">{{ __('new_account') }}</span>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="name" title="{{ __('name') }}" type="text"
                                    :required="true" value="{{ old('name') }}"
                                    placeholder="{{ __('enter_your_account_name') }}" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="account_no" title="{{ __('account_no') }}" type="number"
                                    :required="true" value="{{ old('account_no') }}"
                                    placeholder="{{ __('enter_your_account_no') }}" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="balance" title="{{ __('balance') }}" type="number"
                                    :required="true" value="{{ old('balance') }}"
                                    placeholder="{{ __('enter_your_current_balance') }}" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="note" title="{{ __('note') }}" type="text"
                                    :required="false" value="{{ old('note') }}"
                                    placeholder="{{ __('enter_your_note') }}" />
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
    <!-- end account modal -->
@endsection
