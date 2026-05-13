<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ShopOwnerRequest;
use App\Models\Shop;
use App\Models\ShopUser;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CouponRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\CustomerGroupRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\MoneyTransferRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\RolesRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SaleReturnRepository;
use App\Repositories\ShopCategoryRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\StoreRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\TaxRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = ShopRepository::getAll();
        $shopCategories = ShopCategoryRepository::query()->where('status', 'Active')->get();
        $subsciptions = SubscriptionRepository::query()->where('status', 'Active')->get();
        return view('shop.index', compact('shops', 'shopCategories', 'subsciptions'));
    }

    public function create()
    {
        $shopCategories = ShopCategoryRepository::query()->where('status', 'Active')->get();
        return view('shop.create', compact('shopCategories'));
    }

    public function update(ShopOwnerRequest $request, Shop $shop)
    {
        ShopRepository::updateByRequest($request, $shop);
        return back()->with('success', 'Shop successfully updated');
    }

    public function store(ShopOwnerRequest $request)
    {
        $request['email_verified_at'] = now();
        $user = UserRepository::storeByRequest($request);

        $user->assignRole('admin');
        $shop = ShopRepository::storeByRequest($request, $user);
        $user->shopUser()->attach($shop->id);
        $currency = CurrencyRepository::defaultCurrency($shop, $user);
        GeneralSettingRepository::storeByRequest($request, $shop, $currency);
        return to_route('shop.index')->with('success', 'Shop and Shop owner successfully created');
    }

    public function statusChanage(Shop $shop, $status)
    {
        ShopRepository::statusChanageByRequest($shop, $status);
        return back()->with('success', 'Shop status successfully chanaged');
    }
    public function resetPassword(ResetPasswordRequest $request, User $user)
    {
        UserRepository::resetPassword($request, $user);
        return back()->with('success', 'Password reset successfully');
    }
    public function lifeTimeExpire(Shop $shop)
    {
        ShopRepository::lifeTimeExpire($shop);
        return back()->with('success', 'Shop lifetime expired successfully');
    }
    public function subscriptionChanage(Request $request, Shop $shop)
    {
        ShopSubscriptionRepository::ShopSubscriptionChanage($request, $shop);
        return back()->with('success', 'Shop subscription changed successfully');
    }
    public function delete(Shop $shop)
    {
        AccountRepository::query()->where('shop_id', $shop->id)->delete();
        BrandRepository::query()->where('shop_id', $shop->id)->delete();
        CategoryRepository::query()->where('shop_id', $shop->id)->delete();
        CouponRepository::query()->where('shop_id', $shop->id)->delete();
        CurrencyRepository::query()->where('shop_id', $shop->id)->delete();
        CustomerRepository::query()->where('shop_id', $shop->id)->delete();
        CustomerGroupRepository::query()->where('shop_id', $shop->id)->delete();
        ExpenseRepository::query()->where('shop_id', $shop->id)->delete();
        ExpenseCategoryRepository::query()->where('shop_id', $shop->id)->delete();
        MoneyTransferRepository::query()->where('shop_id', $shop->id)->delete();
        ProductRepository::query()->where('shop_id', $shop->id)->delete();
        $purchases = PurchaseRepository::query()->where('shop_id', $shop->id)->get();
        foreach ($purchases as $purchase) {
            $purchase->purchaseProducts()->delete();
            $purchase->purchaseBatches()->delete();
        }
        PurchaseRepository::query()->where('shop_id', $shop->id)->delete();
        RolesRepository::query()->where('shop_id', $shop->id)->delete();
        StoreRepository::query()->where('shop_id', $shop->id)->delete();
        $sales = SaleRepository::query()->where('shop_id', $shop->id)->get();
        foreach ($sales as $sale) {
            $sale->productSales()->delete();
        }
        SaleRepository::query()->where('shop_id', $shop->id)->delete();
        SaleReturnRepository::query()->where('shop_id', $shop->id)->delete();
        GeneralSettingRepository::query()->where('shop_id', $shop->id)->delete();
        TaxRepository::query()->where('shop_id', $shop->id)->delete();
        SupplierRepository::query()->where('shop_id', $shop->id)->delete();
        UnitRepository::query()->where('shop_id', $shop->id)->delete();
        WarehouseRepository::query()->where('shop_id', $shop->id)->delete();
        TransactionRepository::query()->where('shop_id', $shop->id)->delete();
        ShopUser::where('shop_id', $shop->id)->delete();
        if ($shop->user->manyShop->count() == 1) {
            $shop->user->delete();
        }
        $shop->delete();
        return back()->with('success', 'Shop successfully deleted');
    }
}
