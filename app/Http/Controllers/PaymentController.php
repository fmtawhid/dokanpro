<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Services\OrangePayService;

    class PaymentController extends Controller
    {
        protected $orangePayService;

        public function __construct(OrangePayService $orangePayService)
        {
            $this->orangePayService = $orangePayService;
        }

        public function index()
        {
            return view('payment');
        }

        public function createPayment(Request $request)
        {
            $payment = $this->orangePayService->createPayment(10, 'USD', 'Test payment');

            return response()->json($payment);
        }

        // Add other methods as needed...
    }
