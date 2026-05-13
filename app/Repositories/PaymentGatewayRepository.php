<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\Shop;
use App\Models\PaymentGateway;

class PaymentGatewayRepository extends Repository
{
    public static function model()
    {
        return PaymentGateway::class;
    }

    public static function updateByRequest(PaymentGatewayRequest $request, PaymentGateway $paymentGateway): PaymentGateway
    {
        $config = json_encode($request->config);
        $media = $paymentGateway->media;
        if ($request->hasFile('logo')) {
            $media = MediaRepository::updateOrCreateByRequest($request->logo, 'gateway/logo', 'Image', $media);
        }
        $paymentGateway->update([
            'mode' => $request->mode,
            'title' => $request->title,
            'media_id' => $media->id ?? null,
            'config' => $config,
        ]);
        return $paymentGateway;
    }

    public static function storeOrUpdate(PaymentGatewayRequest $request, PaymentGateway $paymentGateway): PaymentGateway
        {
            $config = json_encode($request->config);
            $shop = Shop::where('user_id', auth()->user()->id)->first();

            $paymentGateway = PaymentGateway::where('shop_id', $shop->id)
                                            ->where('name', $request->name)
                                            ->first();
            $mediaId = null;
            if ($request->hasFile('logo')) {
                $mediaId = MediaRepository::updateOrCreateByRequest($request->logo, 'gateway/logo', 'Image', $paymentGateway?->media)->id;
            }

            return PaymentGateway::updateOrCreate(
                [
                    'shop_id' => $shop->id,
                    'name' => $request->name,
                ],[
                    'alias' => $request->name,
                    'mode' => $request->mode,
                    'title' => $request->title,
                    'media_id' =>  $mediaId,
                    'config' => $config,
                ]
            );
        }

    public static function shopWiseGateway()
    {
        $shop = Shop::where('user_id', auth()->user()->id)->first();
        $paymentGateways = PaymentGateway::where('shop_id', $shop->id)->get();
        return $paymentGateways;
    }




}
