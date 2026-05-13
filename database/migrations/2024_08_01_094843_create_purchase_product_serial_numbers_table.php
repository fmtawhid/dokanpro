<?php

use App\Models\Product;
use App\Models\Purchase;
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
        Schema::create('purchase_product_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->string('serial_number');
            $table->boolean('selling_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_product_serial_numbers');
    }
};
