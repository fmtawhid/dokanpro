<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Repositories\UnitRepository;

class UnitController extends Controller
{
    public function index()
    {
        $units = UnitRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('unit.index', compact('units'));
    }

    public function store(UnitRequest $request)
    {
        UnitRepository::storeByRequest($request);
        return back()->with('success', 'Unit is created successfully!');
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        UnitRepository::updateByRequest($request, $unit);
        return back()->with('success', 'Unit is updated successfully!');
    }

    public function delete(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit is deleted successfully!');
    }
}
