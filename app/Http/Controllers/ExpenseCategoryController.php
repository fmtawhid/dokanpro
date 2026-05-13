<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Repositories\AccountRepository;
use App\Repositories\ExpenseCategoryRepository;
use Illuminate\Http\Request;
use Keygen\Keygen;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $expenseCategories = ExpenseCategoryRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('expenseCategory.index', compact('expenseCategories', 'accounts'));
    }
    public function generateCode()
    {
        $id = Keygen::numeric(8)->generate();
        return $id;
    }
    public function store(ExpenseCategoryRequest $request)
    {
        ExpenseCategoryRepository::storeByRequest($request);
        return back()->with('success', 'Data inserted successfully');
    }
    public function update(ExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        ExpenseCategoryRepository::updateByRequest($request, $expenseCategory);
        return back()->with('success', 'Data updated successfully');
    }
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return back()->with('success', 'Data deleted successfully');
    }
}
