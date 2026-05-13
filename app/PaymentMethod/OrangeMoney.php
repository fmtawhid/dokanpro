<?php

namespace App\PaymentMethod;


class OrangeMoney
{
    public $accessToken;
    public $url;

    public function __construct($clientId, $clientSecret)
    {
        $this->url = 'https://api.sandbox.orange-sonatel.com';
    }


    public function getAccessToken($config)
    {
        $data = 'grant_type=client_credentials&';
        $data .= "client_secret=$config->client_secret&";
        $data .= "client_id=$config->client_id";
        $baseurl = $this->url . '/oauth/token';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $baseurl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $this->accessToken = "Authorization: Bearer " . $response['access_token'];
    }

    public function getPublicKey($pin, $config=null): string
    {
        $this->getAccessToken($config);
        $baseurl = $this->url . '/api/account/v1/publicKeys';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $baseurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [$this->accessToken],
        ]);

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        $makeStringPublicKey = "-----BEGIN PUBLIC KEY-----\n" .  $response['key'] . "\n-----END PUBLIC KEY-----";

        $publicKey = openssl_pkey_get_public($makeStringPublicKey);

        //Encrypt the Customer PIN
        openssl_public_encrypt($pin, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
        $encryptedPin = base64_encode($encrypted);
        return $encryptedPin;
    }

    public function getOtp($pincode, $costumer_msisdn, $config=null): array
    {
        //Encrypt the Customer PIN
        $encryptedPin = $this->getPublicKey($pincode, $config);
        $inputparam = [
            'idType' => 'MSISDN',
            'id' => $costumer_msisdn,
            'encryptedPinCode' => $encryptedPin
        ];

        $data = json_encode($inputparam);
        $baseurl = $this->url . '/api/eWallet/v1/payments/otp';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $baseurl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            $this->accessToken
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        return json_decode($response, true);
    }

    public function checkBalance($pin, $shop_msisdn): array
    {
        //Encrypt the Customer PIN
        $encryptedPin = $this->getPublicKey($pin);

        $inputparam = [
            'idType' => 'MSISDN',
            'id' => $shop_msisdn,
            'encryptedPinCode' => $encryptedPin,
            'wallet' => 'PRINCIPAL'
        ];

        $data = json_encode($inputparam);
        $Balancecurl = curl_init();
        $baseurl = $this->url . '/api/eWallet/v1/account/retailer/balance';

        curl_setopt($Balancecurl, CURLOPT_URL, $baseurl);
        curl_setopt($Balancecurl, CURLOPT_POST, true);
        curl_setopt($Balancecurl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            $this->accessToken
        ]);
        curl_setopt($Balancecurl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($Balancecurl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($Balancecurl);
        return json_decode($response, true);
    }

    public function makePayment($merchant_code, $costumer_msisdn, $otp, $amount, $config)
    {
        $this->getAccessToken($config);
        $inputparam = [
            'customer' => [
                'idType' => 'MSISDN',
                'id' => $costumer_msisdn,
                'otp' => $otp
            ],
            'partner' => [
                'idType' => 'CODE',
                'id' => $merchant_code
            ],
            'amount' => [
                'value' => $amount,
                'unit' => "XOF"
            ],
            'reference' => "INV0001"
        ];

        $data = json_encode($inputparam);

        $paymentcurl = curl_init();
        $baseurl = $this->url . '/api/eWallet/v1/payments/onestep';

        curl_setopt($paymentcurl, CURLOPT_URL, $baseurl);
        curl_setopt($paymentcurl, CURLOPT_POST, true);
        curl_setopt($paymentcurl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            $this->accessToken
        ]);
        curl_setopt($paymentcurl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($paymentcurl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($paymentcurl);
        return json_decode($response, true);
    }
}
