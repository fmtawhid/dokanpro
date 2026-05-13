<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Roles::cases() as $roleName) {
            $role = Role::where('name', $roleName->value)->first();

            if ($role) {
                $permissions = [];
                foreach (config('acl.permissions') as $permission => $roles) {
                    if (in_array($role->name, $roles)) {
                        $permissions[] = $permission;
                    }
                }
                $role->syncPermissions($permissions);
            }
        }
    }
}
