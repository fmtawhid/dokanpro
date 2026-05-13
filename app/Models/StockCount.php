<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $table = 'stock_counts';

    protected $guarded = ['id'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withTrashed();;
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();;
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();;
    }
}
