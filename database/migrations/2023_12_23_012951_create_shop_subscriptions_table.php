<?php

use App\Models\Shop;
use App\Models\Subscription;
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
        Schema::create('shop_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained((new Shop())->getTable());
            $table->foreignId('subscription_id')->constrained((new Subscription())->getTable());
            $table->string('is_current');
            $table->string('payment_gateway')->nullable();
            $table->date('expired_at')->nullable();
            $table->string('payment_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_subscriptions');
    }
};
