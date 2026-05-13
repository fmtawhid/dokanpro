<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $guarded = ['id'];
    protected $table = 'product_purchases';

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
