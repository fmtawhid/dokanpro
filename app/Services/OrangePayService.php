<?php

    namespace App\Services;

    use GuzzleHttp\Client;

    class OrangePayService
    {
        protected $client;
        protected $apiKey;
        protected $apiSecret;
        protected $baseUrl;

        public function __construct()
        {
            $this->client = new Client();
            $this->apiKey = '4d2ceeaa-8ceb-4841-a3a9-4f8a05702092';
            $this->apiSecret = 'bd912517-9db0-4770-9806-61b28414f369';
            $this->baseUrl = 'https://api.orange-pay.com';
        }

        public function createPayment($amount, $currency, $description)
        {
            $response = $this->client->post($this->baseUrl . '/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'description' => $description,
                ],
            ]);

            return json_decode($response->getBody(), true);
        }

        // Add other methods as needed...
    }
