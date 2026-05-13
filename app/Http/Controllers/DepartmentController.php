<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DepartmentRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('department.index', compact('departments'));
    }

    public function store(DepartmentRequest $request)
    {
        DepartmentRepository::storeByRequest($request);
        return back()->with('success', 'Department inserted successfully');
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        DepartmentRepository::updateByRequest($request, $department);
        return back()->withSuccess('Department updated successfully');
    }
    public function delete(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted successfully');
    }
}
