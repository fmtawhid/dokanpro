<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'web')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.role.index', compact('roles'));
    }

    public function create()
    {
        return view('superadmin.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|string|max:255'
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return redirect()
            ->route('superadmin.roles.index')
            ->with('success', __('Role created successfully'));
    }

    public function edit(Role $role)
    {
        return view('superadmin.role.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|string|max:255'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('superadmin.roles.index')
            ->with('success', __('Role updated successfully'));
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super admin', 'admin', 'store', 'customer'])) {
            return back()->with('error', __('Cannot delete system roles'));
        }

        $role->delete();

        return back()->with('success', __('Role deleted successfully'));
    }

    public function permissions(Role $role)
    {
        $allPermissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($item) {
                return explode('.', $item->name)[0];
            });

        $rolePermissions = $role->permissions()
            ->pluck('id')
            ->toArray();

        return view('superadmin.role.permissions', compact('role', 'allPermissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'sometimes|array'
        ]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return back()->with('success', __('Role permissions updated successfully'));
    }
}