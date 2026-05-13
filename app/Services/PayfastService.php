<?php

namespace App\Services;

class PayfastService
{
    public function paymentProcess($request, $config)
    {
        if($request->token_id !=null ):
            return true;
        else:
            return false;
        endif;
    }

    function generateSignature($data, $passPhrase = null)
     {
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    }
}
