@extends('layout.app')
@section('title', __('expenses'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between gap-2 flex-wrap">
                    <span class="list-title">{{ __('expenses') }}</span>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div>
                            <input type="text" name="daterange" class="form-control"
                                value="{{ $startDate }} - {{ $endDate }}" />
                        </div>
                        <div>
                            <button class="btn common-btn" data-toggle="modal" data-target="#createExpense">
                                <i class="fa fa-plus"></i>
                                {{ __('add_expense') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('warehouse') }}</th>
                                    <th>{{ __('category') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('note') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $key => $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ dateFormat($expense->created_at) }}
                                        </td>
                                        <td>{{ $expense->reference_no }}</td>
                                        <td>{{ $expense->warehouse->name }}</td>
                                        <td>{{ $expense->expenseCategory->name }}</td>
                                        <td>{{ numberFormat($expense->amount) }}</td>
                                        <td>{{ $expense->note ?? 'N/A' }}</td>
                                        <td>
                                            <button data-toggle="modal" data-target="#editModal_{{ $expense->id }}"
                                                class="btn btn-sm common-btn"><i class="fa fa-edit"></i></button>
                                            <a id="delete" href="{{ route('expense.destroy', $expense->id) }}"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                            <div id="editModal_{{ $expense->id }}" tabindex="-1" role="dialog"
                                                data-backdrop="static" aria-labelledby="editEXpenseModal" aria-hidden="true"
                                                class="modal fade text-left">
                                                <div role="document" class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header card-header-color">
                                                            <h5 id="editEXpenseModal" class="modal-title">
                                                                {{ __('edit_expense') }}
                                                            </h5>
                                                            <button type="button" data-dismiss="modal" aria-label="Close"
                                                                class="close">
                                                                <span aria-hidden="true">
                                                                    <i class="dripicons-cross text-white"></i>
                                                                </span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('expense.update', $expense->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 form-group">
                                                                        <label class="mb-2">{{ __('expense_category') }}
                                                                            <span class="text-danger">*</span></label>
                                                                        <select name="expense_category_id"
                                                                            class="form-control" required>
                                                                            <option disabled selected>
                                                                                {{ __('select_a_option') }}
                                                                            </option>
                                                                            @foreach ($exCategories as $expense_category)
                                                                                <option
                                                                                    {{ $expense->expense_category_id == $expense_category->id ? 'selected' : '' }}
                                                                                    value="{{ $expense_category->id }}">
                                                                                    {{ $expense_category->name . ' (' . $expense_category->code . ')' }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('expense_category_id')
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-12 form-group mt-2">
                                                                        <label class="mb-2">{{ __('warehouse') }}
                                                                            <span class="text-danger">*</span></label>
                                                                        <select name="warehouse_id" class="form-control"
                                                                            required>
                                                                            <option disabled selected>
                                                                                {{ __('select_a_option') }}
                                                                            </option>
                                                                            @foreach ($warehouses as $warehouse)
                                                                                <option
                                                                                    {{ $expense->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                                                    value="{{ $warehouse->id }}">
                                                                                    {{ $warehouse->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('warehouse_id')
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-12 form-group mt-2">
                                                                        <label class="mb-2">{{ __('payment_method') }} <span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control paymentTypeEdit" name="payment_type" data-expense-id="{{ $expense->id }}">
                                                                            <option disabled selected>
                                                                                {{ __('select_a_option') }}
                                                                            </option>
                                                                            @foreach ($paymentTypes as $key => $type)
                                                                                <option
                                                                                    {{ $expense->payment_type == $key ? 'selected' : '' }}
                                                                                    value="{{ $key }}">
                                                                                    {{ $type }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('payment_type')
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-12 form-group mt-2 accountFieldEdit" id="accountFieldEdit_{{ $expense->id }}" style="display: {{ $expense->payment_type == 'bank' ? 'block' : 'none' }};">
                                                                        <label class="mb-2">{{ __('account') }} <span
                                                                                class="text-danger">*</span></label>
                                                                        <select class="form-control" name="account_id">
                                                                            <option disabled selected>
                                                                                {{ __('select_a_option') }}
                                                                            </option>
                                                                            @foreach ($accounts as $account)
                                                                                <option
                                                                                    {{ $expense->account_id == $account->id ? 'selected' : '' }}
                                                                                    value="{{ $account->id }}">
                                                                                    {{ $account->name }}
                                                                                    ({{ $account->account_no }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('account_id')
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <label class="mb-2">{{ __('note') }}</label>
                                                                    <textarea id="note" name="note" rows="3" class="form-control" placeholder="{{ __('enter_your_note') }}">{{ $expense->note }}</textarea>
                                                                    @error('note')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ __('close') }}</button>
                                                                <button type="submit"
                                                                    class="btn common-btn">{{ __('submit') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <th colspan="5"><strong>{{ __('total') }}</strong></th>
                                <th colspan="2"><strong>{{ numberFormat($expenses->sum('amount')) }}</strong></th>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- expense modal -->
    <div id="createExpense" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="expenseModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('expense.store') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <h5 id="expenseModalLabel" class="modal-title">{{ __('new_expense') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="mb-2">{{ __('expense_category') }} <span
                                        class="text-danger">*</span></label>
                                <select name="expense_category_id" class="form-control">
                                    <option disabled selected>{{ __('select_a_option') }}</option>
                                    @foreach ($exCategories as $expense_category)
                                        <option value="{{ $expense_category->id }}">
                                            {{ $expense_category->name . ' (' . $expense_category->code . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('expense_category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mt-2">
                                <label class="mb-2">{{ __('warehouse') }} <span class="text-danger">*</span></label>
                                <select name="warehouse_id" class="form-control">
                                    <option disabled selected>{{ __('select_a_option') }}</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mt-2">
                                <label class="mb-2">{{ __('amount') }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" required class="form-control"
                                    placeholder="{{ __('enter_your_expense_amount') }}">
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mt-2">
                                <label class="mb-2"> {{ __('payment_method') }} <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="payment_type" id="paymentTypeCreate">
                                    <option disabled selected>{{ __('select_a_option') }}</option>
                                    @foreach ($paymentTypes as $key => $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('payment_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mt-2" id="accountFieldCreate" style="display: none;">
                                <label class="mb-2">{{ __('account') }} <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="account_id" id="accountSelectCreate">
                                    <option disabled selected>{{ __('select_a_option') }}</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                            ({{ $account->account_no }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="mb-2">{{ __('note') }}</label>
                            <textarea name="note" rows="3" class="form-control" placeholder="{{ __('enter_your_note') }}"></textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
@endsection
@push('scripts')
    <script>
        $(function() {
            // Daterange picker
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                window.location.href = "{{ route('expense.index') }}" + "?start_date=" + start +
                    "&end_date=" + end;
            });

            // Toggle account field in create modal
            $('#paymentTypeCreate').on('change', function() {
                if ($(this).val() === 'bank') {
                    $('#accountFieldCreate').show();
                    $('#accountSelectCreate').attr('required', 'required');
                } else {
                    $('#accountFieldCreate').hide();
                    $('#accountSelectCreate').removeAttr('required');
                }
            });

            // Toggle account field in edit modals
            $('.paymentTypeEdit').on('change', function() {
                const expenseId = $(this).data('expense-id');
                if ($(this).val() === 'bank') {
                    $('#accountFieldEdit_' + expenseId).show();
                } else {
                    $('#accountFieldEdit_' + expenseId).hide();
                }
            });
        });
    </script>
@endpush
