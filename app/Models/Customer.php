<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
}
