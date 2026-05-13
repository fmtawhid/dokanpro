<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class CategoryRepository extends Repository
{
    use ShopTrait;
    public static $path = '/category';

    public static function model()
    {
        return Category::class;
    }

    public static function storeByRequest(CategoryRequest $request)
    {
        $thumbnailId = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->image,
                self::$path,
                'Image'
            );
            $thumbnailId = $thumbnail->id;
        }

        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'thumbnail_id' => $thumbnailId,
        ]);
    }

    public static function updateByRequest(CategoryRequest $request, Category $category): Category
    {
        $thumbnailId = null;
        if ($request->hasFile('image')) {
            $thumbnail = MediaRepository::updateOrCreateByRequest(
                $request->image,
                self::$path,
                'Image',
                $category->thumbnail
            );
            $thumbnailId = $thumbnail->id;
        }

        self::update($category, [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'thumbnail_id' => $thumbnailId ? $thumbnailId : $category->thumbnail_id,
        ]);
        return $category;
    }

    public static function importByRequest(Request $request)
    {
        $csv = file($request->file);
        foreach ($csv as $key => $data) {
            $csvArr = explode(',', $data);
            $category = CategoryRepository::query()->where('name', trim($csvArr[1]))->first();
            if ($key != 0) {
                self::create([
                    'name' => $csvArr[0],
                    'parent_id' => $category?->id
                ]);
            }
        }
    }
}
