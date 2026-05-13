@extends('layout.app')
@section('title', __('pending_subscription_approvals'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <x-section-header title="pending_subscription_approvals" />
                </div>
                <div class="card-body">
                    @if($pendingSubscriptions->isEmpty())
                        <div class="alert alert-info">
                            {{ __('no_pending_approvals') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table dataTable table-hover">
                                <thead class="table-bg-color">
                                    <tr>
                                        <th class="not-exported">{{ __('sl') }}</th>
                                        <th>{{ __('shop_name') }}</th>
                                        <th>{{ __('subscription_title') }}</th>
                                        <th>{{ __('payment_gateway') }}</th>
                                        <th>{{ __('payment_status') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('requested_at') }}</th>
                                        <th>{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingSubscriptions as $subscription)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subscription->shop->name ?? '-' }}</td>
                                            <td>{{ $subscription->subscription->title ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-warning">
                                                    {{ ucfirst($subscription->payment_gateway?->value ?? $subscription->payment_gateway) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $subscription->payment_status?->value ?? $subscription->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $subscription->status?->value ?? $subscription->status }}
                                                </span>
                                            </td>
                                            <td>{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" formaction="{{ route('subscription.approve', $subscription) }}" class="btn btn-sm btn-success" title="{{ __('approve') }}">
                                                        <i class="fas fa-check"></i> {{ __('approve') }}
                                                    </button>
                                                </form>
                                                <form method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" formaction="{{ route('subscription.reject', $subscription) }}" class="btn btn-sm btn-danger" title="{{ __('reject') }}" onclick="return confirm('{{ __('are_you_sure') }}')">
                                                        <i class="fas fa-times"></i> {{ __('reject') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
