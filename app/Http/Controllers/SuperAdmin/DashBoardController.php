<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\StoreRepository;
use App\Repositories\SubscriptionRequestRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function dashboard()
    {
        $shops = ShopRepository::query()->orderByDesc('id')->whereMonth('created_at', date('m'))->get();
        $stores = StoreRepository::query()->whereMonth('created_at', date('m'))->get();
        $users = UserRepository::query()->whereMonth('created_at', date('m'))->get();
        $subscriptionPurchages = SubscriptionRequestRepository::query()->whereMonth('created_at', date('m'))->get();
        $shopSubscriptions = ShopSubscriptionRepository::query()->whereMonth('created_at', date('m'))->get();

        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $perMonthShopSubscriptions = ShopSubscriptionRepository::query()->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COALESCE(count(*), 0) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formattedShopSubscriptions = [];
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m');
            $foundDataforShopSubscriptions = $perMonthShopSubscriptions->firstWhere('month', $formattedDate);
            $formattedShopSubscriptions[] = $foundDataforShopSubscriptions ? (int) $foundDataforShopSubscriptions->total : 0;
            $currentDate->addMonth();
        }
        $themeColor = GeneralSettingRepository::query()->whereNull('shop_id')->first()->dark_mode ? 'dark' : 'light';

        return view('dashboard.superAdmin', compact('shops', 'users', 'stores', 'subscriptionPurchages', 'shopSubscriptions', 'formattedShopSubscriptions','themeColor'));
    }
}
