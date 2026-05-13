<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\SaleReturnProduct;

class SaleReturnProductRepository extends Repository
{
    public static function model()
    {
        return SaleReturnProduct::class;
    }
    public static function storeByRequest($request, $saleReturn, $sale)
    {
        foreach ($request->products as $requestProduct) {
            $saleProduct = $sale->productSales()->where('product_id', $requestProduct['id'])->first();
            $product = ProductRepository::query()->where('id', $requestProduct['id'])->first();
            $qty = $saleProduct->qty - $requestProduct['qty'];
            $productTax = 0;
            if ($product->tax) {
                $productTax = $product->price * $product->tax->rate / 100;
            }
            self::create([
                'sale_return_id' => $saleReturn->id,
                'product_id' => $requestProduct['id'],
                'qty' => $qty,
                'discount' => 0,
                'tax_rate' => $product->tax?->rate,
                'tax' => $productTax,
                'total' => ($product->price + $productTax) * $qty,
            ]);
        }
        return $saleReturn;
    }
}
