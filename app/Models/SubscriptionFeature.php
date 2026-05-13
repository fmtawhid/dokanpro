<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeature extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_feature')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function shopSubscriptions()
    {
        return $this->belongsToMany(ShopSubscription::class, 'shop_subscription_features')
            ->withPivot('price', 'expired_at')
            ->withTimestamps();
    }
}
