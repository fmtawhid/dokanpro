@extends('layout.app')
@section('title', __('money_transfers'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('money_transfers') }}</span>
                    <button class="btn common-btn" data-toggle="modal" data-target="#create-money-transfer-modal"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;{{ __('send_money') }}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('reference_no') }}</th>
                                    <th>{{ __('from_account') }}</th>
                                    <th>{{ __('to_account') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th class="not-exported">{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moneyTransfer as $money_transfer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ dateformat($money_transfer->created_at) }}</td>
                                        <td>{{ $money_transfer->reference_no }}</td>
                                        <td>{{ $money_transfer->fromAccount->name ?? 'Deteled' }}</td>
                                        <td>{{ $money_transfer->toAccount->name ?? 'Deteled' }}</td>
                                        <td>{{ numberFormat($money_transfer->amount) }}</td>
                                        <td>
                                            <a id="delete"
                                                href="{{ route('money.transfer.destroy', $money_transfer->id) }}"
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
    </section>
    <!-- Create Money Transfer modal -->
    <div id="create-money-transfer-modal" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <form action="{{ route('money.transfer.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header card-header-color">
                        <span id="exampleModalLabel"
                            class="modal-title list-title text-white">{{ __('send_money') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label class="mb-2">{{ __('from_account') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="from_account_id" required>
                                    <option selected disabled>
                                        {{ __('') }}{{ __('select_a_option') }}</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                            [{{ $account->account_no }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label class="mb-2">{{ __('to_account') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="to_account_id" required>
                                    <option selected disabled>{{ __('select_a_option') }}</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                            [{{ $account->account_no }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label class="mb-2">{{ __('amount') }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" step="any"
                                    placeholder="Enter your amount">
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('transfer') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @if ($errors->all())
        <script>
            Toast.fire({
                icon: 'error',
                title: "Fill in all required fields"
            })
        </script>
    @endif
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                const action = $(this).attr('data-action');
                const formAccount = $(this).attr('data-from_id')
                const toAccount = $(this).attr('data-to_id')
                const amount = $(this).attr('data-amount')

                $("#moneyEditForm").attr('action', action);
                $("#from_account_id").val(formAccount).trigger('change');
                $("#to_account_id").val(toAccount).trigger('change');
                $("#amount").val(amount);
            });
        })
    </script>
@endpush
