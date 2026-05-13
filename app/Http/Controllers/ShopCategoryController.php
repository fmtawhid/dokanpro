<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\ShopCategoryRequest;
use App\Models\ShopCategory;
use App\Repositories\BusinessModulesRepository;
use App\Repositories\ShopCategoryRepository;
use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    public function index()
    {
        $shopCategories = ShopCategoryRepository::getAll();
        $statuses = Status::cases();
        $businessModules = BusinessModulesRepository::getAll();
        return view('shopCategory.index', compact('shopCategories', 'statuses', 'businessModules'));
    }

    public function store(ShopCategoryRequest $request)
    {
        ShopCategoryRepository::storeByRequest($request);
        return back()->with('success', 'Shop category inserted successfully');
    }

    public function update(ShopCategoryRequest $request, ShopCategory $shopCategory)
    {
        ShopCategoryRepository::updateByRequest($request, $shopCategory);
        return back()->withSuccess('Shop category updated successfully');
    }
    public function delete(ShopCategory $shopCategory)
    {
        if ($shopCategory->shops->isNotEmpty()) {
            return back()->with('error', 'You can\'t delete this category because this category already used for shop created');
        }

        $shopCategory->delete();
        return back()->with('success', 'Shop category deleted successfully');
    }
    public function statusChanage(ShopCategory $shopCategory, $status)
    {
        ShopCategoryRepository::statusChanageByRequest($shopCategory, $status);
        return back()->with('success', 'Subscription successfully chanaged');
    }

    public function businessModuleUpdate(Request $request, ShopCategory $shopCategory)
    {
        ShopCategoryRepository::businessModuleUpdateByRequest($request, $shopCategory);
        return back()->with('success', 'Business module successfully chanaged');
    }
}
