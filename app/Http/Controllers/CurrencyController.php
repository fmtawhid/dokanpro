<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = CurrencyRepository::query()->whereNull('shop_id')->orderByDesc('id')->get();
        if ($this->mainShop()) {
            $currencies = CurrencyRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        }
        return view('currency.index', compact('currencies'));
    }

    public function store(CurrencyRequest $request)
    {
        CurrencyRepository::storeByRequest($request);
        return back()->with('success', 'Currency is created successfully!');
    }

    public function update(CurrencyRequest $request, Currency $currency)
    {
        CurrencyRepository::updateByRequest($request, $currency);
        return back()->with('success', 'Currency is updated successfully!');
    }

    public function delete(Currency $currency)
    {
        $currency->delete();
        return back()->with('success', 'Currency is deleted successfully!');
    }
}
