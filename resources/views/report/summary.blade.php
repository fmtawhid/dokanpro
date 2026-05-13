@extends('layout.app')
@section('title', __('summary_report'))
@section('content')
    <section>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <x-section-header title="{{ __('summary_report') }}" />
            </div>
        </div>
        <div class="container-fluid">
            <div class="row mt-4">
                @if (feature('purchases'))
                    <div class="col-md-3 mb-3">
                        <div class="colored-box d-flex flex-column h-100">
                            <div class="d-flex justify-content-between report-box-top w-100">
                                <div class="report-section-card-text">{{ __('purchases') }}</div>
                                <img src="{{ asset('icons/checklist.png') }}" alt=""
                                    class="report-section-card-image">
                            </div>
                            <div class="mt-3 w-100">
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('total_amount') }}</div>
                                    <div class="report-section-card-price">{{ numberFormat($totalPurchasesAmount) }}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('paid') }}</div>
                                    <div class="report-section-card-price">{{ numberFormat($totalPaidAmount) }}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('tax') }}</div>
                                    <div class="report-section-card-price">{{ numberFormat($totalPurchasesTax) }}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('discount') }}</div>
                                    <div class="report-section-card-price">{{ numberFormat($totalPurchasesDiscount) }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('due') }}</div>
                                    <div class="report-section-card-price">
                                        {{ numberFormat($totalPurchasesAmount - $totalPaidAmount) }}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="mt-2 report-section-card-text">{{ __('products') }}</div>
                                    <div class="report-section-card-price">{{ $totalPurchaseProducts }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <div class="colored-box d-flex flex-column h-100">
                        <div class="d-flex justify-content-between report-box-top w-100">
                            <div class="report-section-card-text">{{ __('sales') }}</div>
                            <img src="{{ asset('icons/sales.svg') }}" alt="" class="report-section-card-image">
                        </div>
                        <div class="mt-3 w-100">
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('total_amount') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalSaleAmount) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('tax') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalSaleTax) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('discount') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalSaleDiscount) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('selling_products') }}</div>
                                <div class="report-section-card-price">{{ $totalSaleProducts }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('available_products') }}</div>
                                <div class="report-section-card-price">{{ $totalPurchaseProducts - $totalSaleProducts }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="colored-box d-flex flex-column h-100">
                        <div class="d-flex justify-content-between report-box-top w-100">
                            <div class="report-section-card-text">{{ __('payment_recieved') }}</div>
                            <img src="{{ asset('icons/cash.svg') }}" alt="" class="report-section-card-image">
                        </div>
                        <div class="mt-3 w-100">
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('total_amount') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalPaymentRecieved) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('cash') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalPaymentRecievedCash) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('bank') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($totalPaymentRecievedBank) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('total_recieved_with_cash') }}</div>
                                <div class="report-section-card-price">{{ $totalPaymentRecievedCashCount }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('total_recieved_with_bank') }}</div>
                                <div class="report-section-card-price">{{ $totalPaymentRecievedBankCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="colored-box d-flex flex-column h-100">
                        <div class="d-flex justify-content-between report-box-top w-100">
                            <div class="report-section-card-text">{{ __('profit') }}</div>
                            <img src="{{ asset('icons/cup.svg') }}" alt="" class="report-section-card-image">
                        </div>
                        <div class="mt-3 w-100">
                            <div class="d-flex justify-content-between">
                                <div class="mt-2 report-section-card-text">{{ __('total_amount') }}</div>
                                <div class="report-section-card-price">{{ numberFormat($monthlyProfit) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
