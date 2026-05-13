<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollRequest;
use App\Models\Payroll;
use App\Repositories\AccountRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\PayrollRepository;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = PayrollRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $employees = EmployeeRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->get();

        return view('payroll.index', compact('payrolls', 'employees', 'accounts'));
    }
    public function store(PayrollRequest $request)
    {
        PayrollRepository::storeByRequest($request);
        return back()->with('success', 'Payroll is successfully added!');
    }
    public function update(PayrollRequest $request, Payroll $payroll)
    {
        PayrollRepository::updateByRequest($request, $payroll);
        return back()->with('success', 'Payroll is updated successfully!');
    }
    public function delete(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Payroll is deleted successfully!');
    }
}
