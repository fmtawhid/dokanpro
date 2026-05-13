<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $table = 'product_sales';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
