<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [2, 3, 4, 5, 6, 7];
        $shopIds = [1, 2, 3, 4, 5, 6];
        foreach ($userIds as $key => $id) {
            $user = User::find($id);
            $user->shopUser()->attach($shopIds[$key]);
        }
        $staff = User::find(8);
        $staff->shopUser()->attach(1);
    }
}
