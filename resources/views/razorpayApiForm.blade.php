<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full screen height */
        }
    </style>
</head>
<body>

    <div class="button-container">
        <button id="pay-button">Pay Now</button>
    </div>
<script>
    $(document).ready(function () {
        var amount = {!! json_encode($amount) !!};
        var saleId = {!! json_encode($saleId) !!};
        var user_id = {!! json_encode($user_id) !!};

        console.log("Amount in JavaScript:", amount); // Debugging

        $('#pay-button').click(function (e) {
            e.preventDefault();

            $.ajax({
                url: '/api/payment/razorpay/create-order',
                type: 'POST',
                data: {
                    amount: amount,
                    sale_id: saleId,
                    user_id: user_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        var options = {
                            key: response.data.key,
                            order_id: response.data.order_id,
                            amount: response.data.amount,
                            currency: response.data.currency,
                            name: response.data.name,
                            description: response.data.description,
                            prefill: response.data.prefill,
                            theme: response.data.theme,
                            callback_url: response.data.callback_url,
                            handler: function (paymentResponse) {
                                window.location.href = response.data.callback_url + '&payment_id=' + paymentResponse.razorpay_payment_id;
                                // alert('Payment successful!');
                            },
                            modal: {
                                ondismiss: function () {
                                    console.log('Payment modal closed');
                                },
                            },
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else {
                        alert('Error creating Razorpay order: ' + response.message);
                    }
                },
                error: function (error) {
                    alert('Error creating Razorpay order');
                    console.log(error);
                }
            });
        });
    });
</script>



</body>
</html>
