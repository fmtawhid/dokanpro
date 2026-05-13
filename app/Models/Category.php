<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function product()
    {
    	return $this->hasMany(Product::class)->withTrashed();
    }

    public function thumbnail()
    {
    	return $this->belongsTo(Media::class,'thumbnail_id');
    }

    public function parentCategory()
    {
    	return $this->belongsTo(Category::class,'parent_id')->withTrashed();
    }
}

