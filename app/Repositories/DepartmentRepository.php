<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Traits\ShopTrait;

class DepartmentRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Department::class;
    }

    public static function storeByRequest(DepartmentRequest $request)
    {
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name
        ]);

        return $create;
    }

    public static function updateByRequest(DepartmentRequest $request, Department $department): Department
    {
        self::update($department, [
            'name' => $request->name
        ]);

        return $department;
    }
}
