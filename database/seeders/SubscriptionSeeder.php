<?php

namespace Database\Seeders;

use App\Enums\RecurringType;
use App\Enums\Status;
use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'title' => 'Trail',
                'description' => 'Embark on a journey of endless discovery with our Trail Subscription! Immerse yourself in a curated selection of diverse content, ranging from exclusive articles and insightful newsletters to captivating podcasts and thought-provoking videos. Uncover new perspectives, stay informed, and fuel your curiosity as you traverse the digital landscape. Join our community of Trailblazers and enjoy a seamless exploration experience with our Trail Subscription – where every click leads to a new and exciting adventure.',
                'price' => 0,
                'shop_limit' => 1,
                'product_limit' => 5,
                'recurring_type' => RecurringType::WEEKLY->value,
                'status' => Status::ACTIVE->value
            ], [
                'title' => 'Basic',
                'description' => 'Embark on a journey of endless discovery with our Trail Subscription! Immerse yourself in a curated selection of diverse content, ranging from exclusive articles and insightful newsletters to captivating podcasts and thought-provoking videos. Uncover new perspectives, stay informed, and fuel your curiosity as you traverse the digital landscape. Join our community of Trailblazers and enjoy a seamless exploration experience with our Trail Subscription – where every click leads to a new and exciting adventure.',
                'price' => 29.99,
                'shop_limit' => 2,
                'product_limit' => 25,
                'recurring_type' => RecurringType::MONTHLY->value,
                'status' => Status::ACTIVE->value
            ], [
                'title' => 'Premium',
                'description' => 'Embark on a journey of endless discovery with our Trail Subscription! Immerse yourself in a curated selection of diverse content, ranging from exclusive articles and insightful newsletters to captivating podcasts and thought-provoking videos. Uncover new perspectives, stay informed, and fuel your curiosity as you traverse the digital landscape. Join our community of Trailblazers and enjoy a seamless exploration experience with our Trail Subscription – where every click leads to a new and exciting adventure.',
                'price' => 99.99,
                'shop_limit' => 5,
                'product_limit' => 50,
                'recurring_type' => RecurringType::YEARLY->value,
                'status' => Status::ACTIVE->value
            ]
        ];
        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}
