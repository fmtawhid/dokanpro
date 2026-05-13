<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Employee;
use App\Models\User;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class EmployeeRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return Employee::class;
    }
    public static function storeByRequest(Request $request, User $user)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'staff_id' => $request->staff_id,
        ]);
    }

    public static function updateByRequest(Request $request, Employee $employee)
    {
        $update = self::update($employee, [
            'department_id' => $request->department_id,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'staff_id' => $request->staff_id,
        ]);

        return $update;
    }
}
