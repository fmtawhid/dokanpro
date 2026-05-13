<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->paginate(15);

        return view('superadmin.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('superadmin.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|string|max:255'
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return redirect()
            ->route('superadmin.permissions')
            ->with('success', __('Permission created successfully'));
    }

    public function edit(Permission $permission)
    {
        return view('superadmin.permission.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id . '|string|max:255'
        ]);

        $permission->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('superadmin.permissions')
            ->with('success', __('Permission updated successfully'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', __('Permission deleted successfully'));
    }
}