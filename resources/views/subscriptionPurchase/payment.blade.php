@extends('layout.app')
@section('title', __('payment'))
@section('content')
<script src="https://www.paytabs.com/express_checkout/paytabs-express-checkout.js"></script>

    <style>
        .heading {
            font-size: 23px;
            font-weight: 700;
        }

        .text {
            font-size: 16px;
            font-weight: 500;
            color: #b1b6bd;
        }

        .pricing {
            border: 2px solid #304FFE;
            background-color: #f2f5ff;
        }

        .business {
            font-size: 20px;
            font-weight: 500;
        }

        .plan {
            color: #aba4a4;
        }

        .dollar {
            font-size: 16px;
            color: #6b6b6f;
        }

        .amount {
            font-size: 50px;
            font-weight: 500;
        }

        .year {
            font-size: 20px;
            color: #6b6b6f;
            margin-top: 19px;
        }

        .detail {
            font-size: 22px;
            font-weight: 500;
        }

        .cvv {
            height: 44px;
            width: 73px;
            border: 2px solid #eee;
        }

        .cvv:focus {
            box-shadow: none;
            border: 2px solid #304FFE;
        }

        .email-text {
            height: 55px;
            border: 2px solid #eee;
        }

        .email-text:focus {
            box-shadow: none;
            border: 2px solid #304FFE;
        }

        .payment-button {
            height: 70px;
            font-size: 20px;
        }

        #card-element {
            height: 40px;
        }

        .client_payment_box {
            cursor: pointer;
            transition: border 0.3s;
        }

        .client_payment_box.selected {
            border: 2px solid #007bff;
            border-radius: 5px;
        }

    </style>

    <section>
        <div class="subscription-container-fluid">
            <div class="card-body">
                <div class="container mt-5 mb-5 d-flex justify-content-center">
                    <div class="card p-5">
                        <div>
                            <h4 class="heading">Upgrade your plan</h4>
                            <p class="text">Please make the payment to start enjoying all the features of our premium plan as soon as possible.</p>
                        </div>
                        <div class="pricing p-3 rounded mt-4 d-flex justify-content-between">
                            <div class="images d-flex flex-row align-items-center">
                                <div class="d-flex flex-column ml-4">
                                    <span class="business">{{ $subscription->title ?? '--' }}</span>
                                    <span class="plan">CHANGE PLAN</span>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <sup class="dollar font-weight-bold">{{ $general_settings?->defaultCurrency->symbol }}</sup>
                                <span class="amount ml-1 mr-1">{{ $subscription->price ?? '--' }}</span>
                                <span class="year font-weight-bold">/ {{ $subscription->recurring_type ?? '--' }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="detail">{{ __('select_features') }}</span>
                            <div class="row mt-3">
                                @foreach($features as $feature)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check border rounded p-3">
                                            <input class="form-check-input feature-checkbox" type="checkbox" 
                                                name="features[]" value="{{ $feature->id }}" 
                                                data-price="{{ $feature->pivot->price }}"
                                                id="feature_{{ $feature->id }}" checked>
                                            <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                <strong>{{ $feature->name }}</strong>
                                                <br>
                                                <span class="text small">{{ $general_settings?->defaultCurrency->symbol }} {{ numberFormat($feature->pivot->price) }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="border-top pt-3 mt-3">
                                <h5>Total Price: <span id="total-price">{{ $general_settings?->defaultCurrency->symbol }} {{ numberFormat($subscription->price) }}</span></h5>
                            </div>
                        </div>
                        <span class="detail mt-5">Payment method</span>
                        <div class="credit rounded flex flex-wrap mt-4 row text-center px-2">
                            @foreach($paymentGateways as $paymentGateway)
                                <div class="col-2 d-flex justify-content-center align-items-center client_payment_box py-1"
                                    data-method="{{ $paymentGateway->name }}">
                                    <img src="{{ $paymentGateway->logo }}" class="rounded" width="100" alt="{{ $paymentGateway->name }}">
                                </div>
                            @endforeach
                            <div class="col-2 d-flex justify-content-center align-items-center client_payment_box py-1"
                                data-method="manual" style="background-color: #f0f0f0; border: 2px solid #ddd; border-radius: 5px;">
                                <div style="text-align: center;">
                                    <i class="fa fa-hand-holding-usd" style="font-size: 24px; color: #333;"></i>
                                    <p style="margin-top: 5px; font-size: 12px; color: #333; margin: 5px 0 0 0;">Manual</p>
                                </div>
                            </div>
                        </div>

                        <form role="form" action="{{ route('payment.process', $subscriptionRequest->id) }}" method="post" id="payment-form">
                            @csrf
                            <input type="hidden" name="payment_method" id="selected-method">
                            <input type="hidden" name="token_id" id="token_id">
                            <div id="features-container"></div>

                            <div id="card-element" class="form-control my-3" style="display: none;"></div>
                            <span class="text-danger" id="stripe-error"></span>
                            <span class="text-danger" id="razorpay-error"></span>
                            <div id="paypal-button-container" class="mt-3" style="display: none;"></div>
                            <div id="payfast-widget-container" style="display: none;"></div>

                            <div class="mt-3">
                                <button type="button" class="btn common-btn btn-block payment-button w-100" id="pay-btn" disabled>
                                    Proceed to payment <i class="fa fa-long-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                        <form role="form" action="{{ route('orange.money.payment.process', $subscriptionRequest->id) }}"
                            method="post" id="orange-money-payment-process-form" class="mt-4 d-none">
                            @csrf
                            <input type="hidden" name="payment_method" value="orangepay" />
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <x-inputGroup name="phone_number" title="Phone Number" type="text" :required="true"
                                        placeholder="Enter your orange money phone number" value="" />
                                </div>
                                <div class="col-md-12">
                                    <x-inputGroup name="pin_code" title="Pin Code" type="password" value="" :required="true"
                                        placeholder="Enter your orange money pin code" />
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn common-btn btn-block payment-button w-100">
                                    Proceed to payment <i class="fa fa-long-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript" src="https://sandbox.payfast.co.za/eng/process"></script>
    <script src="https://www.payfast.co.za/onsite/engine.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal_client_id }}&currency=USD"></script>

    <script>
        $(document).ready(function() {
            let stripe, elements, cardElement;
            let formSubmitted       = false;
            let paypalInitialized   = false;

            $('#pay-btn').prop('disabled', true);

            $(document).on('click', '.client_payment_box', function() {
                $(this).addClass('selected').siblings().removeClass('selected');
                $('#pay-btn').prop('disabled', false);

                let paymentMethod = $(this).data('method');
                console.log(paymentMethod);
                $('#orange-money-payment-process-form').addClass('d-none');

                console.log('Selected Payment Method:', paymentMethod);
                $('#selected-method').val(paymentMethod);

                // Hide all payment UI elements first
                $('#paypal-button-container').hide();
                $('#card-element').hide();
                $('#payfast-widget-container').hide();
                destroyStripeCardElement();

                if (paymentMethod === 'paypal') {
                    $('#paypal-button-container').show();
                    $('#pay-btn').hide();
                    if (!paypalInitialized) {
                        initiatePaypal();
                        paypalInitialized = true;
                    }
                } else if (paymentMethod === 'payfast') {
                    $('#payfast-widget-container').show();
                    initiatePayFastWidget();
                    $('#pay-btn').show();
                } else if (paymentMethod === 'stripe') {
                    $('#card-element').show();
                    initializeStripe();
                    $('#pay-btn').show();
                } else if (paymentMethod === 'orangepay') {
                    $('#orange-money-payment-process-form').removeClass('d-none');
                    $('#pay-btn').hide();
                } else if (paymentMethod === 'manual') {
                    // For manual payment, just show the button
                    $('#pay-btn').show();
                } else {
                    // For other payment gateways, show the button
                    $('#pay-btn').show();
                }
            });

            // Initialize Stripe Elements
            function initializeStripe() {
                if (!stripe) {
                    stripe = Stripe('{{ $stripe_publish_key }}');
                    elements = stripe.elements();
                }
                if (!cardElement) {
                    cardElement = elements.create('card');
                    cardElement.mount('#card-element');
                }
            }

            // Destroy Stripe Elements
            function destroyStripeCardElement() {
                if (cardElement) {
                    cardElement.destroy();
                    cardElement = null;
                }
            }

            // Handle payment form submission
            $('#payment-form').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitted) return;
                formSubmitted = true;
                $('#pay-btn').prop('disabled', true);

                // Add selected features to form
                let selectedFeatures = [];
                $('.feature-checkbox:checked').each(function() {
                    selectedFeatures.push($(this).val());
                });
                
                // Clear previous feature inputs and add new ones
                $('#features-container').html('');
                $.each(selectedFeatures, function(index, featureId) {
                    $('#features-container').append('<input type="hidden" name="features[]" value="' + featureId + '">');
                });

                // Handle Stripe token creation
                if ($('#selected-method').val() === 'stripe') {
                    stripe.createToken(cardElement).then(function(result) {
                        if (result.error) {
                            $('#stripe-error').text(result.error.message);
                            $('#pay-btn').prop('disabled', false);
                            formSubmitted = false;
                        } else {
                            $('#token_id').val(result.token.id);
                            $('#payment-form').off('submit').submit();
                        }
                    });
                } else {
                    // For other payment methods, just submit
                    $('#payment-form').off('submit').submit();
                }
            });

            // Main pay button click handler
            $('#pay-btn').on('click', function() {
                // Collect selected features
                let selectedFeatures = [];
                $('.feature-checkbox:checked').each(function() {
                    selectedFeatures.push($(this).val());
                });
                
                // Clear previous feature inputs and add new ones
                $('#features-container').html('');
                $.each(selectedFeatures, function(index, featureId) {
                    $('#features-container').append('<input type="hidden" name="features[]" value="' + featureId + '">');
                });

                if ($('#selected-method').val() === 'stripe') {
                    $('#payment-form').submit();
                } else if ($('#selected-method').val() === 'razorpay') {
                    initiateRazorpay();
                } else if ($('#selected-method').val() === 'paypal') {
                    initiatePaypal();
                } else if ($('#selected-method').val() === 'paytabs') {
                    initiatePayTabs(generateTransactionId());
                } else if ($('#selected-method').val() === 'paystack') {
                    initiatePayStack();
                } else if ($('#selected-method').val() === 'payfast') {
                    initiatePayFastWidget();
                } else if ($('#selected-method').val() === 'manual') {
                    $('#payment-form').off('submit').submit();
                }
            });

            // Razorpay initialization
            function initiateRazorpay() {
                let options = {
                    key: "{{ $razorpay_publish_key }}",
                    amount: "{{ $subscription->price * 100 }}",
                    currency: "INR",
                    description: "Subscription Payment",
                    handler: function(response) {
                        $('#token_id').val(response.razorpay_payment_id);
                        $('#payment-form').off('submit').submit();

                    },
                    prefill: {
                        email: "{{ auth()->user()->email }}",
                        contact: "{{ auth()->user()->phone ?? '' }}"
                    },
                    theme: {
                        color: "#304FFE"
                    }
                };
                let rzp = new Razorpay(options);
                rzp.open();
            }

            // PayPal initialization
            function initiatePaypal() {
                if (!paypalInitialized) {
                    if (typeof paypal !== 'undefined') {
                        $('#paypal-button-container').empty();

                        paypal.Buttons({
                            style: {
                                layout: 'horizontal',
                                color:  'blue',
                                label:  'pay',
                                height: 40
                            },
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    intent: "CAPTURE",
                                    purchase_units: [{
                                        amount: {
                                            currency_code: "USD",
                                            value: "{{ $subscription->price }}"
                                        }

                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    console.log('Order captured:', details.id);
                                    $('#token_id').val(details.id);
                                    $('#payment-form').off('submit').submit();
                                }).catch(function(err) {
                                    console.error('Capture failed:', err);
                                    alert('Payment capture failed. Please try again.');
                                });
                            },
                            onError: function(err) {
                                console.error("PayPal Checkout error:", err);
                                alert('An error occurred during PayPal Checkout. Please try again.');
                                $('#pay-btn').prop('disabled', false);
                            }
                        }).render('#paypal-button-container');
                        paypalInitialized = true;
                    } else {
                        console.error('PayPal script is not loaded!');
                        alert('Unable to load PayPal script. Please reload the page.');
                    }
                }
            }

            // Paystack initialization
            function initiatePayStack() {
                let selectedCurrency = "ZAR";
                let handler = PaystackPop.setup({
                    key: "{{ $paystack_publish_key }}",
                    email: "{{ auth()->user()->email }}",
                    amount: "{{ $subscription->price * 100 }}",
                    currency: selectedCurrency,
                    callback: function(response) {
                        $('#token_id').val(response.reference);
                        $('#payment-form').off('submit').submit();
                    },
                    onClose: function() {
                        alert('Transaction cancelled.');
                    }
                });
                handler.openIframe();
            }

            function initiatePayFastWidget() {
                let paymentMethod = $('#selected-method').val();
                if (paymentMethod === 'payfast') {
                    let transactionData = {
                        'merchant_id': '{{ $payfast_client_id }}',
                        'merchant_key': '{{ $payfast_client_secret }}',
                        'amount': '{{ $subscription->price }}',
                        'item_name': 'Subscription Payment',
                        'email_address': '{{ auth()->user()->email }}',
                        'cancel_url': '{{ route("payment.cancel") }}',
                        'notify_url': '{{ route("payment.notify") }}',
                    };
                    let queryString = $.param(transactionData);
                    let payfastUrl = `https://sandbox.payfast.co.za/eng/process?${queryString}`;

                    // Dimensions of the pop-up
                    let width = 800;
                    let height = 600;

                    // Cross-browser calculations for centering
                    let left = window.screenLeft + (window.innerWidth - width) / 2;
                    let top = window.screenTop + (window.innerHeight - height) / 2;

                    // Fallback for browsers with outerWidth/outerHeight
                    if (window.outerWidth && window.outerHeight) {
                        left = window.screenX + (window.outerWidth - width) / 2;
                        top = window.screenY + (window.outerHeight - height) / 2;
                    }

                    // Open the pop-up window
                    let payfastWindow = window.open(
                        payfastUrl,
                        'PayFast Payment',
                        `width=${width},height=${height},scrollbars=no,toolbar=no,location=no,status=no,menubar=no,left=${Math.max(left, 0)},top=${Math.max(top, 0)}`
                    );

                    // Check if the pop-up is blocked
                    if (!payfastWindow || payfastWindow.closed || typeof payfastWindow.closed === 'undefined') {
                        alert('Pop-up blocked. Please allow pop-ups for this website.');
                        return;
                    }

                    // Polling to check if the window is closed
                    let pollTimer = window.setInterval(function () {
                        if (payfastWindow.closed) {
                            window.clearInterval(pollTimer);
                            $.ajax({
                                url: '{{ route("payment.notify") }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    paymentMethod: 'payfast',
                                    transaction_id: '123456789',
                                    payment_status: 'success',
                                    merchant_id: '{{ $payfast_client_id }}',
                                    merchant_key: '{{ $payfast_client_secret }}',
                                    amount: '{{ $subscription->price }}',
                                    item_name: 'Subscription Payment',
                                    email_address: '{{ auth()->user()->email }}',
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        $('#token_id').val(response.transaction_id);
                                        $('#payment-form').off('submit').submit();
                                    } else {
                                        alert('Payment verification failed. Please try again.');
                                    }
                                },
                                error: function () {
                                    alert('An error occurred during payment verification. Please try again.');
                                }
                            });
                        }
                    }, 500);
                }
            }


        });

        // Handle feature checkbox changes for dynamic price calculation
        $(document).on('change', '.feature-checkbox', function() {
            let totalPrice = {{ $subscription->price }};
            let baseCurrency = '{{ $general_settings?->defaultCurrency->symbol }}';
            
            $('.feature-checkbox:checked').each(function() {
                let price = parseFloat($(this).data('price')) || 0;
                totalPrice += price;
            });
            
            // Format the number
            let formattedPrice = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(totalPrice);
            
            $('#total-price').text(baseCurrency + ' ' + formattedPrice);
        });
    </script>
@endpush


