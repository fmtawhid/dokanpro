<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWarehouse extends Model
{
	protected $table = 'product_warehouse';
    protected $guarded = ['id'];

    public function scopeFindProductWithVariant($query, $product_id, $variant_id, $warehouse_id)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['variant_id', $variant_id],
            ['warehouse_id', $warehouse_id]
        ]);
    }

    public function scopeFindProductWithoutVariant($query, $product_id, $warehouse_id)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['warehouse_id', $warehouse_id]
        ]);
    }
    public function warehouseProduct(){
        return $this->belongsTo(Product::class)->withTrashed();;
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
}
