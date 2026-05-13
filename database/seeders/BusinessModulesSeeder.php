<?php

namespace Database\Seeders;

use App\Models\BusinessModules;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table
        BusinessModules::truncate();
        
        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Repopulate the table
        $businessModules = config('businessmodules');
        foreach ($businessModules as $value) {
            BusinessModules::create([
                'name' => $value['name'],
                'label' => $value['label'],
            ]);
        }
    }
}
