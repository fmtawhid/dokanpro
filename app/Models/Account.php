<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
