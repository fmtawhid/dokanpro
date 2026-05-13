<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->hasMany(Product::class)->withTrashed();
    }
    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }
}
