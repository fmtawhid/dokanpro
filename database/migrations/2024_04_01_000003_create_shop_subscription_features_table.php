<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('shop_subscription_features')) {
            Schema::create('shop_subscription_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_subscription_id')->constrained('shop_subscriptions')->cascadeOnDelete();
            $table->foreignId('subscription_feature_id')->constrained('subscription_features')->cascadeOnDelete();
            $table->float('price'); // Price paid for this feature
            $table->date('expired_at')->nullable(); // Expiration date for this feature
            $table->timestamps();

            $table->unique(['shop_subscription_id', 'subscription_feature_id'], 'shop_sub_feature_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_subscription_features');
    }
};
