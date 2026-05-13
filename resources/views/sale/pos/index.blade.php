<!DOCTYPE html>
<html lang="en" class="{{ $general_settings->dark_mode == 1 ? 'dark' : 'light' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png"
        href="{{ isset($general_settings->favicon->file) && $general_settings->favicon->file ? $general_settings->favicon->file : asset('/logo/small_logo.png') }}" />
    <title>
        {{ isset($general_settings->site_title) && $general_settings->site_title ? $general_settings->site_title : 'Ready POS' }}
        - {{ __('pos') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}" type="text/css">
    <style>
        .primary-boder-color{
            border: 3px solid #37BDF2 !important;
        }

        .primary-bg-color-light {
            background: #B9E7FC !important;
        }


        .primary-border-color-light {
            border-color: var(--theme-secondary-color) !important;
        }

        .hover-primary-border-color:hover {
            border-color: #37BDF2 !important;

        }

        .border-red-500{
            border-color: #FF0000 !important;

        }

        .border-blue-500{
            border-color: #7393B3 !important;

        }
        .border-success-500{
            border-color: #37BDF2 !important;

        }

        .border-slate-500{
            border-color: #808080 !important;

        }
    </style>
    @vite('resources/css/app.css')

</head>
@include('sale.pos.components.style')

<body>
    <div class="min-h-screen bg-blue-50">
        <!-- header -->
        @include('sale.pos.components.header')
        <div class="py-[8px] px-4 bg-blue-50 print:hidden dark:bg-slate-500">
            <div class="grid gap-x-2 gap-y-5 xl:gap-y-0 grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 2xl:grid-cols-5">
                <!-- category and brand section -->
                @include('sale.pos.components.category_brand_section')
                <!-- Product -->
                @include('sale.pos.components.product_section')

                <!-- column three -->
                <div class="col-span-1 sm:col-span-3 lg:col-span-3 2xl:col-span-2">
                    <!-- customer section -->
                    @include('sale.pos.components.customer_section')
                    <!-- Cart and Calculate section -->
                    @include('sale.pos.components.cart_section')
                    <!-- Payment Method section -->
                    @include('sale.pos.components.payment_method_section')
                    <!-- process button section -->
                    @include('sale.pos.components.process_button_section')
                </div>
            </div>
        </div>
    </div>
    <!-- logout modal -->
    @include('sale.pos.components.logout_modal')
    <!-- Store Wallet Modal -->
    @include('sale.pos.components.store_wallet_modal')
    <!-- Customer Add Modal -->
    @include('sale.pos.components.customer_create_modal')
    <div class="fixed z-10 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="stripeModal">
        <div class="flex items-center justify-center min-h-screen w-full">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all mx-auto" style="width: 500px;, height: 50px;" >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div id="card-element" class="w-full"></div>
                        <div id="card-errors" role="alert" class="text-red-500"></div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white text-blue-50 primary-bg-color sm:ml-3 sm:w-auto sm:text-sm" id="submit-payment">
                        {{ __('pay') }}
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="close-payment">
                        {{ __('close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed z-10 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="orangePayModal">
        <div class="flex items-center justify-center min-h-screen w-full">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all mx-auto" style="width: 50%; height: 100%;">
                <form id="orangePayForm">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <input type="hidden" name="payment_method" value="orangepay" />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="disabledTextInput" class="form-label">Phone Number</label>
                                <input type="text" class="form-control py-5" name="phone_number" style="width: 100%"/>
                            </div>
                            <div class="col-md-6 pt-3">
                                <label for="disabledTextInput" class="form-label pt-3">Pin Code</label>
                                <input type="password" class="form-control py-5" name="pin_code"  style="width: 100%"/>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white text-blue-50 primary-bg-color sm:ml-3 sm:w-auto sm:text-sm" id="submit-orangepay-payment">
                            {{ __('pay') }}
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="close-orange-pay">
                            {{ __('close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Pos Script -->
    @include('sale.pos.script')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://www.payfast.co.za/onsite/engine.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal_client_id ?? '' }}&currency=USD"></script>
    <script>
        var payfast_client_id = '{{ $payfast_client_id ?? null}}' ;
        var payfast_client_secret = '{{ $payfast_client_secret ?? null }}';
        $(document).ready(function () {
            $(".payment_option").on("click", function () {
                $(".payment_option").removeClass("border-4 border-red-500 border-blue-500 border-success-500 border-gray-500 border-slate-500");
                let bgClass = $(this).data("bg");
                let borderColor = "border-success-500";
                if (bgClass.includes("red")) {
                    borderColor = "border-red-500";
                } else if (bgClass.includes("blue")) {
                    borderColor = "border-blue-500";
                } else if (bgClass.includes("slate")) {
                    borderColor = "border-slate-500";
                } else if (bgClass.includes("green")) {
                    borderColor = "border-success-500";
                }
                $(this).addClass("border-4 " + borderColor);
            });
        });
    </script>
</body>
</html>
