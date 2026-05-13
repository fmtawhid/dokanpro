<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ProductWarehouse;

class ProductWarehouseRepository extends Repository
{
    public static function model()
    {
        return ProductWarehouse::class;
    }
}
