<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\ShopCategory;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $subscriptions = Subscription::where('status', Status::ACTIVE->value)
            ->orderBy('price')
            ->get();
        $shopCategories = ShopCategory::where('status', Status::ACTIVE->value)
            ->orderBy('id')
            ->get();
        $subscriptionFeatures = SubscriptionFeature::whereHas('subscriptions', function ($query) {
            $query->where('status', Status::ACTIVE->value);
        })->orderBy('id')->get();

        return view('landing', compact('subscriptions', 'shopCategories', 'subscriptionFeatures'));
    }
}
