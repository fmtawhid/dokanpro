<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    protected $guarded = ['id'];

    public function fromAccount()
    {
    	return $this->belongsTo(Account::class)->withTrashed();
    }

    public function toAccount()
    {
    	return $this->belongsTo(Account::class)->withTrashed();
    }
}
