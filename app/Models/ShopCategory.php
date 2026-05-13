<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => Status::class,
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function businessModules()
    {
        return $this->belongsToMany(BusinessModules::class);
    }
}
