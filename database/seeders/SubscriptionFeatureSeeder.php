<?php

namespace Database\Seeders;

use App\Models\SubscriptionFeature;
use Illuminate\Database\Seeder;

class SubscriptionFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'HRM',
                'slug' => 'hrm',
                'description' => 'Human Resource Management Module',
            ],
            [
                'name' => 'Accounting',
                'slug' => 'accounting',
                'description' => 'Accounting Module',
            ],
            [
                'name' => 'Return',
                'slug' => 'return',
                'description' => 'Return Management Module',
            ],
            [
                'name' => 'Expense',
                'slug' => 'expense',
                'description' => 'Expense Management Module',
            ],
        ];

        foreach ($features as $feature) {
            SubscriptionFeature::updateOrCreate(
                ['slug' => $feature['slug']],
                $feature
            );
        }
    }
}
