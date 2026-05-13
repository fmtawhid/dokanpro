<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'customer_group_id');
    }
}
