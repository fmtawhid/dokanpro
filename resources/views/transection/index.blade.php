@extends('layout.app')
@section('title', __('Transections'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ __('Transections') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('payment_method') }}</th>
                                    <th>{{ __('transection_type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('purpose') }}</th>
                                    <th>{{ __('date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transections as $transection)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transection->user->name }}</td>
                                        <td>{{ $transection->payment_method }}</td>
                                        <td>{{ $transection->transection_type->value }}</td>
                                        <td>{!! numberFormat($transection->amount) !!}</td>
                                        <td>{{ $transection->purpose ?? 'N/A' }}</td>
                                        <td>{{ dateFormat($transection->date) }}</td>
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
