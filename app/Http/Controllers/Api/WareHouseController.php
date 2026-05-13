<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    public function index()
    {
        $shopId = $this->mainShop()->id;
        $request = request();

        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 15;
        $skip = ($page * $perPage) - $perPage;

        $warehouses = WarehouseRepository::query()->where('shop_id', $shopId)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->latest('id');

        $total = $warehouses->count();

        return $this->json("All warehouses", [
            'total' => $total,
            'warehouses' => WarehouseResource::collection($warehouses->skip($skip)->take($perPage)->get()),
        ]);
    }

    public function show(Warehouse $warehouse){
        return $this->json("Warehouse", [
            'warehouse' => WarehouseResource::make($warehouse),
        ]);
    }

    public function store(WarehouseRequest $request){
        $warehouse = WarehouseRepository::storeByRequest($request);
        return $this->json("Warehouse created successfully", [
            'warehouse' => WarehouseResource::make($warehouse),
        ]);
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse){
        $warehouse = WarehouseRepository::updateByRequest($request, $warehouse);
        return $this->json("Warehouse updated successfully", [
            'warehouse' => WarehouseResource::make($warehouse),
        ]);
    }

    public function destroy(Warehouse $warehouse){
        $warehouse->delete();
        return $this->json("Warehouse deleted successfully", []);
    }
}
