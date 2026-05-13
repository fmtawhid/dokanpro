<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionRequestStatus;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use Keygen\Keygen;

class SubscriptionRequestRepository extends Repository
{
    public static function model()
    {
        return SubscriptionRequest::class;
    }

    public static function storeByRequest(Subscription $subscription, $selectedFeatures = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'subscription_id' => $subscription->id,
            'payment_status' => PaymentStatus::UNPAID->value,
            'status' => SubscriptionRequestStatus::PENDING->value,
            'payment_gateway' => PaymentGateway::STRIPE->value,
            'selected_features' => $selectedFeatures ?? [],
        ]);
    }

    public static function updateByRequest(SubscriptionRequest $subscriptionRequest)
    {
        $transactionId = Keygen::numeric(16)->generate();
        return self::update($subscriptionRequest, [
            'payment_status' => PaymentStatus::PAID->value,
            'status' => SubscriptionRequestStatus::SUCCESS->value,
            'transaction_id' => $transactionId
        ]);
    }

    public static function requestFailed(SubscriptionRequest $subscriptionRequest)
    {
        return self::update($subscriptionRequest, [
            'status' => SubscriptionRequestStatus::FAILED->value,
        ]);
    }
}
