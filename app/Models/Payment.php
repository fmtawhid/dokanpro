<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'paying_method' => PaymentMethod::class,
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class)->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
