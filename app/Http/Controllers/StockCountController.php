<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\StockCountRepository;
use App\Repositories\WarehouseRepository;

class StockCountController extends Controller
{
    public function index()
    {
        $warehouses = WarehouseRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $brands = BrandRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $categories = CategoryRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $stockCounts = StockCountRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();

        return view('stockCount.index', compact('warehouses', 'brands', 'categories', 'stockCounts'));
    }

    public function store(Request $request)
    {
        StockCountRepository::storeByRequest($request);
        return back()->with('success', 'Stock count is successfully added!');
    }
}
