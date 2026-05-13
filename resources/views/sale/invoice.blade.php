<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('web/logos/fav-icon.png') }}">
    <title>Order Invoice</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .common-btn {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #29aae1;
            color: #FFF;
            width: 100%;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1px dotted #ddd;
        }

        td,
        th {
            padding: 7px 0;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

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

    <div style="max-width:400px;margin:0 auto">
        <div class="hidden-print">
            <table>
                <tr>
                    <td>
                        <a href="{{ route('sale.pos') }}" class="btn common-btn"><i class="fa fa-arrow-left"></i>Back To
                            Order</a>
                    </td>
                    <td>
                        <button onclick="window.print();" class="btn common-btn">
                            <i class="fa fa-print"></i> Print Now
                        </button>
                    </td>
                </tr>
            </table>
            <br>
        </div>
        <div id="receipt-data">
            <div class="centered">
                <img src="{{ $generalsettings->logo->file ?? asset('logo/logo.svg') }}" height="42"
                    style="margin-top: -10px; margin-bottom: 0px;filter: brightness(100%);">
                <p style="margin-top: 0; font-size: 10px; line-height: 20px;">
                    {{ $generalsettings?->address }}<br>
                    <i class="fas fa-phone-alt" style="font-size:10px"></i> {{ $generalsettings?->phone }} <br>
                    <i class="fas fa-globe" style="font-size:10px"></i> {{ $generalsettings?->email }}
                </p>
            </div>

            <p>
                Customer: {{ $sale->customer?->name }}
                <br>
                Country : {{ $sale->customer?->country }} / City: {{ $sale->customer?->city }}
                <br>
                Address: {{ $sale->customer?->address }} / State: {{ $sale->customer?->state }}
                <br>
                Phone No: {{ $sale->customer?->phone_number }}
                <br>
                Pick Date: {{ Carbon\Carbon::parse($sale->created_at)->format('M d, Y') }}
                <br>
                Order ID: <strong>{{ $sale->reference_no }}</strong>
            </p>

            <table class="table-data">
                <tbody>
                    @foreach ($sale->productSales as $product)
                        <tr>
                            <td style="padding-bottom: 0" colspan="2">
                                <h4 style="margin: 0">{{ $product->product->name }}</h4>
                                {{ $product->qty }} x
                                {{ numberFormat($product->net_unit_price) }} -
                                [Discount:{{ $product->discount }}] +
                                [{{ $product->qty }} x Tax:{{ $product->tax }}]
                            </td>
                            <td style="text-align:right;vertical-align:bottom;padding-bottom: 0">
                                {{ numberFormat($product->total) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table style="margin-top: 20px">
                <tbody>
                    <tr style="background-color:#ddd;">
                        <td style="padding: 5px;width:20%">Total PCs: {{ $sale->total_qty }}</td>
                        <td style="padding: 5px;width:25%;text-align:center">Paid By: {{ $sale->payment_method }}</td>
                        <td style="padding: 5px;width:30%;text-align:center">Discount: {{ numberFormat($sale->coupon_discount) }}</td>
                        <td style="padding: 5px;width:25%;text-align:right">Total:
                            <strong>{{ numberFormat($sale->grand_total) }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td class="centered" colspan="3">Thank you for choosing {{ $generalsettings?->site_title }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <script type="text/javascript">
        function auto_print() {
            window.print()
        }
        setTimeout(auto_print, 1000);
    </script>
</body>

</html>
