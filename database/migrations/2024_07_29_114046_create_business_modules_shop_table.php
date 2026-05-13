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
        Schema::create('business_modules_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_modules_id')->constrained((new \App\Models\BusinessModules())->getTable())->onDelete('cascade');
            $table->foreignId('shop_id')->constrained((new \App\Models\Shop())->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_modules_shop');
    }
};
