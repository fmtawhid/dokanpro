<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'created_by' => 2,
            'shop_id' => 1,
            'user_id' => 3,
            'name' => 'ReadyPOS Store',
            'description' => 'ReadyPOS Store: Your one-stop destination for emergency preparedness essentials. Quality supplies for safety, security, and peace of mind.',
            'email' => 'readyposstore@gmail.com',
            'phone_number' => '012033232115',
            'address' => 'Flat#-7th floor, H# 19, Road# 08, Shekhertek Adabor Thana Mohammedpur Dhaka -1207, Bangladesh.',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'postal_code' => '1207',
            'country' => 'Bangladesh',
            'status' => 'Active',
        ]);
    }
}
