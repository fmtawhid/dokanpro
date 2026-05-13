@extends('layout.app')
@section('title', __('payrolls'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="payrolls" />
                    <x-create-button name="add_payroll" target="createPayrollModal" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('created_by') }}</th>
                                    <th>{{ __('employee') }}</th>
                                    <th>{{ __('account') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('note') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payroll->user->name }}</td>
                                        <td>{{ $payroll->employee->user->name ?? 'N/A' }}</td>
                                        <td>{{ $payroll->account->name ?? 'N/A' }}</td>
                                        <td>{{ numberFormat($payroll->amount) }}</td>
                                        <td>{{ $payroll->date }}</td>
                                        <td>{{ $payroll->note ?? 'N/A' }}</td>
                                        <td>
                                            <x-edit-button target="editPayrollModal_{{ $payroll->id }}" />
                                            <x-delete-button route="{{ route('payroll.delete', $payroll->id) }}" />
                                        </td>
                                    </tr>
                                    <div id="editPayrollModal_{{ $payroll->id }}" tabindex="-1" role="dialog"
                                        data-backdrop="static" aria-labelledby="editPayrollModalLabel_{{ $payroll->id }}"
                                        aria-hidden="true" class="modal fade text-left">
                                        <div role="document" class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('payroll.update', $payroll->id) }}" method="POST">
                                                    @method('put')
                                                    @csrf
                                                    <x-modal-header header="edit_payroll"
                                                        id="editPayrollModalLabel_{{ $payroll->id }}" />

                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <x-select name="account_id" title="account"
                                                                    :required="true" placeholder="select_a_option">
                                                                    @foreach ($accounts as $account)
                                                                        <option
                                                                            {{ $account->id == $payroll->account_id ? 'selected' : '' }}
                                                                            value="{{ $account->id }}">
                                                                            {{ $account->name }}
                                                                            ({{ $account->account_no }})
                                                                        </option>
                                                                    @endforeach
                                                                </x-select>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-select name="employee_id" title="employee"
                                                                    :required="true" placeholder="select_a_option">
                                                                    @foreach ($employees as $employee)
                                                                        <option
                                                                            {{ $employee->id == $payroll->employee_id ? 'selected' : '' }}
                                                                            value="{{ $employee->id }}">
                                                                            {{ $employee->user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </x-select>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup type="number" name="amount" title="amount"
                                                                    :required="true" placeholder="enter_your_salary_amount"
                                                                    value="{{ $payroll->amount }}" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <x-inputGroup name="date" title="date" type="date"
                                                                    :required="true" placeholder="Enter_your_salary_date"
                                                                    value="{{ $payroll->date }}" />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <x-textarea-group name="note" title="note"
                                                                    :required="false" placeholder="enter_your_payroll_note"
                                                                    value="{{ $payroll->note }}" />
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
    <!-- Create Payroll Modal -->
    <div id="createPayrollModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createPayrollModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('payroll.store') }}" method="POST">
                    @csrf
                    <x-modal-header header="new_payroll" id="createPayrollModalLabel" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-select name="account_id" title="account" :required="true" placeholder="select_a_option">
                                    @foreach ($accounts as $account)
                                        <option {{ $account->id == old('account_id') ? 'selected' : '' }}
                                            value="{{ $account->id }}">
                                            {{ $account->name }}
                                            ({{ $account->account_no }})
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-select name="employee_id" title="employee" :required="true"
                                    placeholder="select_a_option">
                                    @foreach ($employees as $employee)
                                        <option {{ $employee->id == old('employee_id') ? 'selected' : '' }}
                                            value="{{ $employee->id }}">
                                            {{ $employee->user->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup type="number" name="amount" title="amount" :required="true"
                                    placeholder="enter_your_salary_amount" value="" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-inputGroup name="date" title="date" type="date" :required="true"
                                    placeholder="Enter_your_salary_date" value="" />
                            </div>
                            <div class="col-md-12">
                                <x-textarea-group name="note" title="note" :required="false"
                                    placeholder="enter_your_payroll_note" value="" />
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
