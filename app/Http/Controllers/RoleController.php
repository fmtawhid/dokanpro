<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Repositories\RolesRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RolesRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('role.index', compact('roles'));
    }

    public function store(RoleRequest $request)
    {
        RolesRepository::storeByRequest($request);
        return back()->with('success', 'Role is created successfully!');
    }
    public function update(RoleRequest $request, Role $role)
    {
        RolesRepository::updateByRequest($request, $role);
        return back()->with('success', 'Role is updated successfully!');
    }

    public function permission($id)
    {
        $role = RolesRepository::find($id);
        $rolePermissions = Role::findByName($role->name)->permissions->pluck('name')->toArray();
        $permissions = Permission::all();
        return view('role.permission', compact('role', 'permissions', 'rolePermissions'));
    }

    public function setPermission(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|array',
        ]);

        $permissions = [
            'root',
            'signout'
        ];
        foreach ($request->permission as $key => $permission) {
            if (str_ends_with($key, '.create')) {
                $permissions[] = str_replace('.create', '.store', $key);
            }
            if (str_ends_with($key, '.edit')) {
                $permissions[] = str_replace('.edit', '.update', $key);
            }
            $permissions[] = $key;
        }

        $role->syncPermissions($permissions);
        return back()->with('success', 'Permission is updated successfully');
    }
}
