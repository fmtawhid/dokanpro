<?php

namespace App\Models;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'payment_gateway' => PaymentGateway::class,
        'payment_status' => PaymentStatus::class,
        'status' => SubscriptionRequestStatus::class,
        'selected_features' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
