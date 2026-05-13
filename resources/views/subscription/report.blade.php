@extends('layout.app')
@section('title', __('subscription_reports'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="subscription_reports" />
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('shop_name') }}</th>
                                    <th>{{ __('subscription_title') }}</th>
                                    <th>{{ __('is_current') }}</th>
                                    <th>{{ __('payment_gateway') }}</th>
                                    <th>{{ __('payment_status') }}</th>
                                    <th>{{ __('expired_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shopSubscriptions as $shopSubscription)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $shopSubscription->shop->name }}</td>
                                        <td>{{ $shopSubscription->subscription->title }}</td>
                                        <td>{{ $shopSubscription->is_current }}</td>
                                        <td>{{ $shopSubscription->payment_gateway }}</td>
                                        <td>{{ $shopSubscription->payment_status }}</td>
                                        <td>{{ dateFormat($shopSubscription->expired_at) }}</td>
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
