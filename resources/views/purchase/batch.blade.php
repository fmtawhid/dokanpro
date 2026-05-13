@extends('layout.app')
@section('title', __('batches'))
@section('content')
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="list-title">{{ __('batches') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table dataTable table-hover" style="width: 100%">
                            <thead class="table-bg-color">
                                <tr>
                                    <th class="not-exported">{{ __('sl') }}</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('product_name') }}</th>
                                    <th>{{ __('purchase_quantity') }}</th>
                                    <th>{{ __('selling_quantity') }}</th>
                                    <th>{{ __('purchase_date') }}</th>
                                    <th>{{ __('expired_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchasebatches as $batch)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $batch->name }}</td>
                                        <td>{{ $batch->product->name }}</td>
                                        <td>{{ $batch->qty }}</td>
                                        <td>{{ $batch->sale_qty }}</td>
                                        <td>{{ dateFormat($batch->purchase_date) }}</td>
                                        <td>
                                            @php
                                                $expire_date = dateFormat($batch->expire_date);
                                                $today_date = date('Y-m-d');
                                                $date_diff = (strtotime($expire_date) - strtotime($today_date)) / (60 * 60 * 24);
                                            @endphp
                                            {{ $expire_date }}
                                            @if ($date_diff < 0)
                                                <span class="badge badge-danger">{{ __('expired') }}</span>
                                            @elseif ($date_diff <= 7)
                                                <span class="badge badge-warning">{{ __('expire_soon') }}</span>
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
