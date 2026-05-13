<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ExpenseCategory;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class ExpenseCategoryRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return ExpenseCategory::class;
    }
    public static function storeByRequest(Request $request)
    {
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return $create;
    }

    public static function updateByRequest(Request $request, ExpenseCategory $expenseCategory)
    {
        $update = self::update($expenseCategory, [
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return $update;
    }
}
