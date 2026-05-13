@extends('layout.app')
@section('title', __('balance_sheet'))
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <span class="list-title">{{ __('balance_sheet') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('account_no') }}</th>
                                    <th>{{ __('credit') }}</th>
                                    <th>{{ __('debit') }}</th>
                                    <th>{{ __('balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->account_no }}</td>
                                        <td>{{ numberFormat($account->transactions()->where('transection_type', 'Credit')->sum('amount')) }}
                                        </td>
                                        <td>{{ numberFormat($account->transactions()->where('transection_type', 'Debit')->sum('amount')) }}
                                        </td>
                                        <td>{{ numberFormat($account->total_balance) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
