<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use App\Repositories\HolidayRepository;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = HolidayRepository::query()
            ->where('shop_id', $this->mainShop()->id)
            ->orderByDesc('id')
            ->get();
        return view('holiday.index', compact('holidays'));
    }
    public function store(HolidayRequest $request)
    {
        HolidayRepository::storeByRequest($request);
        return back()->with('success', 'Holiday is created successfully!');
    }
    public function update(HolidayRequest $request, Holiday $holiday)
    {
        HolidayRepository::updateByRequest($request, $holiday);
        return back()->with('success', 'Holiday is updated successfully!');
    }
    public function delete(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday is deleted successfully!');
    }
}
