<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use App\Models\PaymentGateway;
use App\Models\Shop;

class PaypalService
{
    private function getAccessToken($config, $baseUrl)
    {
        try {
            $client = new Client();
            $response = $client->post($baseUrl . '/v1/oauth2/token', [
                'auth' => [$config->client_id, $config->client_secret],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            return $data['access_token'];
        } catch (Exception $e) {
            return false;
        }
    }

    public function paymentProcess($request, $config)
    {
        try {
            $shop = Shop::where('user_id', auth()->user()->id)->first();
            $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', $shop->id)->first();
            if($paymentGateway->mode == 'live'):
                $baseUrl =  'https://api-m.paypal.com';
            else:
                $baseUrl =  'https://api-m.sandbox.paypal.com';
            endif;

            $accessToken = $this->getAccessToken($config, $baseUrl);
            $client = new Client();
            $token  = $request->token_id;

            $response = $client->get($baseUrl. "/v2/checkout/orders/{$token}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
            ]);
            if($response ):
                return json_decode($response->getBody()->getContents(), true);
            else:
                return false;
            endif;
        } catch (Exception $e) {
            return false;
        }
    }
}
