<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
