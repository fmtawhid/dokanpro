<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRequest;
use Illuminate\Http\Request;
use App\Models\Tax;
use App\Repositories\TaxRepository;

class TaxController extends Controller
{
    public function index()
    {
        $taxs = TaxRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('tax.index', compact('taxs'));
    }

    public function store(TaxRequest $request)
    {
        TaxRepository::storeByRequest($request);
        return back()->with('success', 'Tax is created successfully!');
    }

    public function update(TaxRequest $request, Tax $tax)
    {
        TaxRepository::updateByRequest($request, $tax);
        return back()->with('success', 'Tax is updated successfully!');
    }

    public function delete(Tax $tax)
    {
        $tax->delete();
        return back()->with('success', 'Tax is deleted successfully!');
    }
}
