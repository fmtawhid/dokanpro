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
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('expired_at') }}</th>
                                    <th class="not-exported">{{ __('actions') }}</th>
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
                                        <td>
                                            <span class="badge @if($shopSubscription->payment_status->value == 'Paid') badge-success @else badge-warning @endif">
                                                {{ $shopSubscription->payment_status->value }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge @if($shopSubscription->status->value == 'Approved') badge-success @elseif($shopSubscription->status->value == 'Pending') badge-warning @else badge-danger @endif">
                                                {{ $shopSubscription->status->value }}
                                            </span>
                                        </td>
                                        <td>{{ dateFormat($shopSubscription->expired_at) }}</td>
                                        <td>
                                            @if($shopSubscription->status->value == 'Pending' && auth()->user()->id == 1)
                                                <form method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" formaction="{{ route('subscription.approve', $shopSubscription) }}" class="btn btn-sm btn-success" title="{{ __('approve') }}">
                                                        <i class="fas fa-check"></i> {{ __('approve') }}
                                                    </button>
                                                </form>
                                                <form method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" formaction="{{ route('subscription.reject', $shopSubscription) }}" class="btn btn-sm btn-danger" title="{{ __('reject') }}" onclick="return confirm('{{ __('are_you_sure') }}')">
                                                        <i class="fas fa-times"></i> {{ __('reject') }}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
@endsection
