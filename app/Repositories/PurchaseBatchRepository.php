<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseBatch;

class PurchaseBatchRepository extends Repository
{
    public static function model()
    {
        return PurchaseBatch::class;
    }

    public static function storeByRequest($product, Purchase $purchase, $date)
    {
        self::create([
            'purchase_id' => $purchase->id,
            'name' => $product['batch'],
            'product_id' => $product['id'],
            'qty' => $product['qty'],
            'sale_qty' => $product['qty'],
            'expire_date' => $product['expire_date'],
            'purchase_date' => $date ?? now(),
        ]);
    }

    public static function batchProductSale(Product $product, $qty)
    {
        $purchaseBatch = self::query()->where('sale_qty', '>', 0)->where('product_id', $product->id)->first();
        if (!$purchaseBatch) {
            return;
        }
        $dueQty = $qty;
        if (isset($purchaseBatch->sale_qty) && $purchaseBatch->sale_qty < $qty) {
            $dueQty = $qty - $purchaseBatch->sale_qty;
            $update = self::update($purchaseBatch, [
                'sale_qty' => $purchaseBatch->sale_qty - $purchaseBatch->sale_qty,
            ]);
        }

        $purchaseBatch = self::query()->where('sale_qty', '>', 0)->where('product_id', $product->id)->first();
        $update = self::update($purchaseBatch, [
            'sale_qty' => ($purchaseBatch->sale_qty - $dueQty) >  0 ? ($purchaseBatch->sale_qty - $dueQty) : 0,
        ]);
        return $update;
    }
}
