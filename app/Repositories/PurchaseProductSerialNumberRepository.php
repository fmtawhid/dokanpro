<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProductSerialNumber;

class PurchaseProductSerialNumberRepository extends Repository
{
    public static function model()
    {
        return PurchaseProductSerialNumber::class;
    }

    public static function storeUpByRequest(Purchase $purchase, Product $product, $serialNumber)
    {
        return self::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'serial_number' => $serialNumber,
            'selling_status' => false,
        ]);
    }
}
