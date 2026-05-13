<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSuperAdmin();
        $this->createAdmin();
        $this->groceryShopOwner();
        $this->pharmacyOwner();
        $this->electronicsShopOwner();
        $this->restaurantOwner();
        $this->clothingOwner();
        $this->createStore();
    }
    private function createSuperAdmin()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'thumbnail_id' => $image
        ]);

        $userAdmin->assignRole('super admin');
    }
    private function createAdmin()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function groceryShopOwner()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Grocery Shop Owner',
            'email' => 'groceryshop@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function pharmacyOwner()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Pharmacy Owner',
            'email' => 'pharmacy@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function electronicsShopOwner()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Electronics Shop Owner',
            'email' => 'electronics@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function restaurantOwner()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Restaurant Owner',
            'email' => 'restaurant@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function clothingOwner()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $userAdmin = User::factory()->create([
            'name' => 'Clothing Shop Owner',
            'email' => 'clothing@example.com',
            'thumbnail_id' => $image
        ]);
        $userAdmin->assignRole('admin');
    }
    private function createStore()
    {
        $image = Media::factory()->create([
            'src' => 'defualt/profile.jpg',
            'path' => 'user/',
        ])->id;
        $staffUser = User::factory()->create([
            'name' => 'Store',
            'email' => 'store@example.com',
            'thumbnail_id' => $image
        ]);
        $staffUser->assignRole('store');
    }
}
