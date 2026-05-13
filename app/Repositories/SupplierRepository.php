<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SupplierRequest;
use App\Models\Media;
use App\Models\Supplier;
use App\Traits\ShopTrait;

class SupplierRepository extends Repository
{
    use ShopTrait;
    
    private static $path = '/supplier';

    public static function model()
    {
        return Supplier::class;
    }
    public static function storeByRequest(SupplierRequest $request)
    {
        $thumbnail_id = null;
        if ($request->hasFile('image')) {
            $thumbnail = (new MediaRepository())->storeByRequest($request->image, self::$path);
            $thumbnail_id =  $thumbnail->id;
        }

        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'thumbnail_id' => $thumbnail_id,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'vat_number' => $request->vat_number,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);
    }

    public static function updateByRequest(SupplierRequest $request, Supplier $supplier)
    {
        $thumbnail_id = $supplier->thumbnail_id;
        if ($request->hasFile('image')) {
            $thumbnail = self::documentUpdateOrCreateByRequest($request, $supplier->thumbnail);
            $thumbnail_id =  $thumbnail->id;
        }

        $update = self::update($supplier, [
            'name' => $request->name,
            'thumbnail_id' => $thumbnail_id,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'vat_number' => $request->vat_number,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);
        return $update;
    }

    public static function documentUpdateOrCreateByRequest(SupplierRequest $request, Media $media = null): Media
    {
        if ($media) {
            if ($request->hasFile('image')) {
                return MediaRepository::updateByRequest(
                    $request->image,
                    self::$path,
                    'Image',
                    $media
                );
            }
        }
        if ($request->hasFile('image')) {
            return MediaRepository::storeByRequest(
                $request->image,
                self::$path,
                'Image'
            );
        }
    }
}
