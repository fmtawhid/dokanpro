@extends('layout.app')
@section('title', __('sale_returns'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('sale_returns') }}</span>
                    <div>
                        <button class="btn common-btn" data-toggle="modal" data-target="#return-modal"><i class="fa fa-plus"
                                aria-hidden="true"></i>
                            {{ __('add_return') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table purchase-list dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('reference') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('biller') }}</th>
                                    <th>{{ __('total_product') }}</th>
                                    <th>{{ __('total_discount') }}</th>
                                    <th>{{ __('total_tax') }}</th>
                                    <th>{{ __('grand_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saleReturns as $saleReturn)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $saleReturn->reference_no }}</td>
                                        <td>{{ dateFormat($saleReturn->created_at) }}</td>
                                        <td>{{ $saleReturn->user->name }}</td>
                                        <td>{{ $saleReturn->item }}</td>
                                        <td>{{ numberFormat($saleReturn->total_discount) }}</td>
                                        <td>{{ numberFormat($saleReturn->total_tax) }}</td>
                                        <td>{{ numberFormat($saleReturn->grand_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="return-modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="returnModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('sale.return.search') }}" method="POST">
                    @csrf
                    <div class="modal-header card-header-color">
                        <span id="returnModalLabel"
                            class="modal-title list-title text-white">{{ __('search_sales') }}</span>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="fa fa-times text-white"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-2">{{ __('invoice_no') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_no" class="form-control mb-2"
                                        placeholder="{{ __('enter_your_invoice_no') }}">
                                    @error('invoice_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn common-btn">{{ __('search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
