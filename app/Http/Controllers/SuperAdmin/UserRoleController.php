<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return view('superadmin.user.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load('roles');
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return view('superadmin.user.show', compact('user', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,id'
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role assigned successfully to ' . $user->name);
    }

    public function removeRole(User $user, Role $role)
    {
        $user->removeRole($role);

        return back()->with('success', 'Role removed successfully from ' . $user->name);
    }
}