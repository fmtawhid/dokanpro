<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    <title>{{ __('product_print') }}</title>
    <style>
        .table-bg-color {
            background: #29aae1;
            color: #fff;
        }

        .main-content {
            max-width: 1060px;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        @media print {

            .hidden-print {
                display: none !important;
            }

            @page {
                margin: 1.5cm 0.5cm 0.5cm;
            }

            @page: first {
                margin-top: 0.5cm;
            }

            tbody::after {
                content: '';
                display: block;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="main-content m-auto">
        <div class="centered mt-5">
            <img src="{{ $generalsettings->logo->file ?? asset('logo/logo.svg') }}" height="42"
                style="margin-top: -10px; margin-bottom: 0px;filter: brightness(100%);">
            <p style="margin-top: 0; font-size: 10px; line-height: 20px;">
                {{ $generalsettings?->address }}<br>
                <i class="fas fa-phone-alt" style="font-size:10px"></i> {{ $generalsettings?->phone }} <br>
                <i class="fas fa-globe" style="font-size:10px"></i> {{ $generalsettings?->email }}
            </p>
            <h4>{{ __('product_report') }}</h4>
        </div>
        <div class="hidden-print mt-3 mb-2 text-center">
            <a href="{{ route('report.product') }}" class="btn btn-danger">{{ __('back') }}</a>
            <button type="button" class="btn common-btn" onclick="window.print();">{{ __('print') }}</button>
        </div>
        <table class="table table-bordered text-center">
            <thead class="table-bg-color">
                <tr>
                    <th>{{ __('sl') }}</th>
                    <th width="300px">{{ __('product') }}</th>
                    <th>{{ __('total_stock') }}</th>
                    <th>{{ __('total_purchase') }}</th>
                    <th>{{ __('total_purchase_price') }}</th>
                    <th>{{ __('total_sale') }}</th>
                    <th>{{ __('total_selling_price') }}</th>
                    <th>{{ __('total_sale_return') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_purchase = 0;
                    $total_purchase_price = 0;
                    $total_sale = 0;
                    $total_selling_price = 0;
                    $total_sale_return = 0;
                @endphp
                @foreach ($products as $product)
                    @php
                        $total_purchase += $product->purchaseProducts()->sum('qty');
                        $total_purchase_price += $product->purchaseProducts()->sum('total');
                        $total_sale += $product->saleProducts()->sum('qty');
                        $total_selling_price += $product->saleProducts()->sum('total');
                        $total_sale_return += $product->saleReturnProducts()->sum('qty');
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <img src="{{ $product->thumbnail->file ?? asset('defualt/defualt.jpg') }}"
                                    height="30" width="30">
                                <div>{{ $product->name }}</div>
                            </div>
                        </td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ $product->purchaseProducts()->sum('qty') }}</td>
                        <td>{{ numberFormat($product->purchaseProducts()->sum('total')) }}</td>
                        <td>{{ $product->saleProducts()->sum('qty') }}</td>
                        <td>{{ numberFormat($product->saleProducts()->sum('total')) }}</td>
                        <td>{{ $product->saleReturnProducts()->sum('qty') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"><strong>{{ __('total') }}</strong></td>
                    <td colspan="2"><strong>{{ numberFormat($total_purchase_price) }}</strong></td>
                    <td colspan="2"><strong>{{ numberFormat($total_selling_price) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        function auto_print() {
            window.print()
        }
        setTimeout(auto_print, 1000);
    </script>
</body>

</html>
