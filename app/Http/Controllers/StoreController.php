<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = StoreRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('store.index', compact('stores'));
    }

    public function create()
    {
        $statuses = Status::cases();
        return view('store.create', compact('statuses'));
    }

    public function store(StoreRequest $request)
    {
        $subscription = $this->mainShop()?->currentSubscriptions()?->subscription;
        $stores = StoreRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        if ($this->mainShop()->is_lifetime == 0 && $stores->count() >= $subscription->shop_limit ?? 0) {
            return back()->withError('You have extend your limit');
        }

        $request['email_verified_at'] = now();
        $shopManager = UserRepository::storeByRequest($request, $this->mainShop());
        
        $shopManager->shopUser()->attach($this->mainShop()->id);

        StoreRepository::storeByRequest($request, $shopManager);
        $shopManager->assignRole('store');
        return back()->with('success', 'Store inserted successfully');
    }

    public function edit(Store $store)
    {
        return view('store.edit', compact('store'));
    }

    public function update(StoreRequest $request, Store $store)
    {
        StoreRepository::updateByRequest($request, $store);
        UserRepository::updateByRequest($request, $store->user);
        return back()->with('success', 'Store updated successfully');
    }
}
