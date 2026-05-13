<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessModules extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function contains($name)
    {
        return $this->$name == 'yes' ? true : false;
    }

    public function shopCategories()
    {
        return $this->belongsToMany(ShopCategory::class);
    }
}
