<?php

namespace App\Models;

use App\Enums\SalesType;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'type' => SalesType::class,
    ];

    public function customer()
    {
    	return $this->belongsTo(Customer::class,'customer_id');
    }

    public function warehouse()
    {
    	return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'created_by');
    }

    public function productSales()
    {
    	return $this->hasMany(ProductSale::class,'sale_id');
    }
}
