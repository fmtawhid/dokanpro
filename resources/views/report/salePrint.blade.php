<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    <title>{{ __('sale_print') }}</title>
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
            <h4>{{ __('sale_report') }}</h4>
        </div>
        @if (!isset($pdf))
            <div class="hidden-print mt-3 mb-2 text-center">
                <a href="{{ route('report.sales') }}" class="btn btn-danger">{{ __('back') }}</a>
                <button type="button" class="btn common-btn" onclick="window.print();">{{ __('print') }}</button>
            </div>
        @endif
        <table class="table table-bordered text-center">
            <thead class="table-bg-color">
                <tr>
                    <th class="not-exported">{{ __('sl') }}</th>
                    <th>{{ __('date') }}</th>
                    <th>{{ __('biller') }}</th>
                    <th>{{ __('total_qty') }}</th>
                    <th>{{ __('total_discount') }}</th>
                    <th>{{ __('total_tax') }}</th>
                    <th>{{ __('total_price') }}</th>
                    <th>{{ __('grand_total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ dateFormat($sale->created_at) }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td>{{ $sale->total_qty }}</td>
                        <td>{{ numberFormat($sale->total_discount) }}</td>
                        <td>{{ numberFormat($sale->total_tax) }}</td>
                        <td>{{ numberFormat($sale->total_price) }}</td>
                        <td>{{ numberFormat($sale->grand_total) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('total') }}</th>
                    <th></th>
                    <th></th>
                    <th>{{ $sales->sum('total_qty') }}</th>
                    <th>{{ numberFormat($sales->sum('total_discount')) }}</th>
                    <th>{{ numberFormat($sales->sum('total_tax')) }}</th>
                    <th>{{ numberFormat($sales->sum('total_price')) }}</th>
                    <th>{{ numberFormat($sales->sum('grand_total')) }}</th>
                </tr>
            </tfoot>
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
