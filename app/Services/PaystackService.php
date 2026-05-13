<?php

namespace App\Services;

class PaystackService
{
    public function paymentProcess($request, $config)
    {
        $reference = $request->token_id;
        $public_key = $config->secret_key;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL  => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING  => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT  => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $public_key",
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);

        if($response && $response->data->status == 'success'){
            return true;
        }

        return false;
    }
}
