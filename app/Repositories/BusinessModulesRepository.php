<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\BusinessModules;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class BusinessModulesRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return BusinessModules::class;
    }
    public static function updateByRequest(Request $request)
    {
        return self::update(self::mainShop()->businessModules, [
            'purchases' => $request->purchases ? 'yes' : 'no',
            'add_sale' => $request->add_sale ? 'yes' : 'no',
            'pos' => $request->pos ? 'yes' : 'no',
            'expenses' => $request->expenses ? 'yes' : 'no',
            'account' => $request->account ? 'yes' : 'no',
            'tables' => $request->tables ? 'yes' : 'no',
            'modifiers' => $request->modifiers ? 'yes' : 'no',
            'staffs_management' => $request->staffs_management ? 'yes' : 'no',
            'bookings' => $request->bookings ? 'yes' : 'no',
            'kitchen' => $request->kitchen ? 'yes' : 'no'
        ]);
    }
}
