<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\RecurringType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\ShopSubscription;
use App\Models\SubscriptionRequest as SubscriptionRequestModel;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SubscriptionFeatureRepository;
use App\Repositories\SubscriptionRequestRepository;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = SubscriptionRepository::getAll();
        $recurringTypes = RecurringType::cases();
        $statuses = Status::cases();
        $features = SubscriptionFeatureRepository::getAll();
        return view('subscription.index', compact('subscriptions', 'recurringTypes', 'statuses', 'features'));
    }

    public function store(SubscriptionRequest $request)
    {
        $subscription = SubscriptionRepository::storeByRequest($request);
        
        // Attach features if provided
        if ($request->has('features')) {
            $features = [];
            foreach ($request->input('features') as $featureId) {
                $price = $request->input('feature_price_' . $featureId, 0);
                $features[$featureId] = ['price' => $price];
            }
            if (!empty($features)) {
                $subscription->features()->attach($features);
            }
        }
        
        return back()->with('success', 'Subscription is created successfully');
    }

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        SubscriptionRepository::updateByRequest($request, $subscription);
        
        // Sync features if provided
        if ($request->has('features')) {
            $features = [];
            foreach ($request->input('features') as $featureId) {
                $price = $request->input('feature_price_' . $featureId, 0);
                $features[$featureId] = ['price' => $price];
            }
            $subscription->features()->sync($features);
        } else {
            $subscription->features()->sync([]);
        }
        
        return back()->with('success', 'Subscription is updated successfully');
    }

    public function statusChanage(Subscription $subscription, $status)
    {
        SubscriptionRepository::statusChanageByRequest($subscription, $status);
        return back()->with('success', 'Subscription successfully chanaged');
    }

    public function report()
    {
        $shopSubscriptions = ShopSubscriptionRepository::getAll();
        if ($this->mainShop()) {
            $shopSubscriptions = ShopSubscriptionRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        }
        return view('subscription.report', compact('shopSubscriptions'));
    }

    public function pendingApprovals()
    {
        $pendingSubscriptions = ShopSubscriptionRepository::query()
            ->where('status', 'Pending')
            ->with(['shop', 'subscription'])
            ->orderByDesc('created_at')
            ->get();
        return view('subscription.pending_approvals', compact('pendingSubscriptions'));
    }

    public function approve(ShopSubscription $shopSubscription)
    {
        if ($shopSubscription->status->value !== 'Pending') {
            return back()->with('error', 'This subscription cannot be approved. Current status: ' . $shopSubscription->status->value);
        }

        ShopSubscriptionRepository::approveSubscription($shopSubscription);
        
        // Also update the subscription request
        $subscriptionRequest = SubscriptionRequestModel::where([
            'subscription_id' => $shopSubscription->subscription_id,
            'status' => 'Pending'
        ])->orderByDesc('created_at')->first();

        if ($subscriptionRequest) {
            SubscriptionRequestRepository::approveRequest($subscriptionRequest);
        }

        return back()->with('success', 'Subscription approved successfully. Payment status updated to Paid.');
    }

    public function reject(ShopSubscription $shopSubscription, Request $request)
    {
        if ($shopSubscription->status->value !== 'Pending') {
            return back()->with('error', 'This subscription cannot be rejected. Current status: ' . $shopSubscription->status->value);
        }

        ShopSubscriptionRepository::rejectSubscription($shopSubscription);
        
        // Also update the subscription request
        $subscriptionRequest = SubscriptionRequestModel::where([
            'subscription_id' => $shopSubscription->subscription_id,
            'status' => 'Pending'
        ])->orderByDesc('created_at')->first();

        if ($subscriptionRequest) {
            SubscriptionRequestRepository::rejectRequest($subscriptionRequest);
        }

        // Mark subscription as no longer current if rejected
        $shopSubscription->update(['is_current' => 'No']);

        return back()->with('success', 'Subscription rejected successfully.');
    }
}
