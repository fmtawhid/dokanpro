<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Traits\ShopTrait;

class BrandRepository extends Repository
{
    use ShopTrait;
    private static $path = '/brand';

    public static function model()
    {
        return Brand::class;
    }

    public static function storeByRequest(BrandRequest $request)
    {
        $thumbnailId = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::storeByRequest($request->image, self::$path, 'Image');
            $thumbnailId = $thumbnail->id;
        }
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'title' => $request->title,
            'thumbnail_id' => $thumbnailId
        ]);

        return $create;
    }

    public static function updateByRequest(BrandRequest $request, Brand $brand): Brand
    {
        $thumbnail = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::updateOrCreateByRequest(
                $request->image,
                self::$path,
                'Image',
                $brand->thumbnail
            );
        }
        self::update($brand, [
            'title' => $request->title,
            'thumbnail_id' => $thumbnail ? $thumbnail->id : $brand->thumbnail_id,
        ]);

        return $brand;
    }
}
