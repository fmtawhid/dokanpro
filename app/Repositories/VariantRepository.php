<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Variant;
use App\Traits\ShopTrait;

class VariantRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Variant::class;
    }
    public static function storyByRequest($variantName)
    {
        return self::create([
            'name' => $variantName,
        ]);
    }
}
