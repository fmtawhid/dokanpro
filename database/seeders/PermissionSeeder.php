<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('acl.permissions');

        if (empty($permissions) || !is_array($permissions)) {
            return;
        }

        foreach ($permissions as $permission => $value) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
