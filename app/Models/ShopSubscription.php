<?php

namespace App\Models;

use App\Enums\IsHas;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionApprovalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSubscription extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'payment_gateway' => PaymentGateway::class,
        'payment_status' => PaymentStatus::class,
        'is_current' => IsHas::class,
        'status' => SubscriptionApprovalStatus::class,
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function features()
    {
        return $this->belongsToMany(SubscriptionFeature::class, 'shop_subscription_features')
            ->withPivot('price', 'expired_at')
            ->withTimestamps();
    }
}
