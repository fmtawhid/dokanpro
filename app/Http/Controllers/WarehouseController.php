<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;

class WarehouseController extends Controller
{

    public function index()
    {
        $warehouses = WarehouseRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('warehouse.index', compact('warehouses'));
    }

    public function store(WarehouseRequest $request)
    {
        WarehouseRepository::storeByRequest($request);
        return back()->with('success', 'Warehouse is created successfully!');
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        WarehouseRepository::updateByRequest($request, $warehouse);
        return back()->with('success', 'Warehouse is updated successfully');
    }

    public function delete(Warehouse $warehouse)
    {
        if ($warehouse->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this warehouse!');
        }
        $warehouse->delete();
        return back()->with('success', 'Warehouse is deleted successfully');
    }

    public function trash()
    {
        $warehouses = WarehouseRepository::query()->onlyTrashed()->where('shop_id', $this->mainShop()->id)->orderByDesc('deleted_at')->get();
        return view('warehouse.trash', compact('warehouses'));
    }

    public function restore($id)
    {
        $warehouse = Warehouse::onlyTrashed()->find($id);
        if (!$warehouse) {
            return back()->with('error', 'Warehouse not found');
        }
        if ($warehouse->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to restore this warehouse!');
        }
        $warehouse->restore();
        return back()->with('success', 'Warehouse restored successfully');
    }

    public function forceDelete($id)
    {
        $warehouse = Warehouse::onlyTrashed()->find($id);
        if (!$warehouse) {
            return back()->with('error', 'Warehouse not found');
        }
        if ($warehouse->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this warehouse!');
        }
        
        // Check if warehouse has any purchases
        $purchaseCount = $warehouse->purchases()->count();
        if ($purchaseCount > 0) {
            return back()->with('error', 'Cannot delete this warehouse! It has ' . $purchaseCount . ' purchase(es) associated with it.');
        }
        
        $warehouse->forceDelete();
        return back()->with('success', 'Warehouse permanently deleted');
    }
}