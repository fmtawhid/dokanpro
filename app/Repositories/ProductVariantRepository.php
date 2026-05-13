<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ProductVariant;

class ProductVariantRepository extends Repository
{
    public static function model()
    {
        return ProductVariant::class;
    }
}
