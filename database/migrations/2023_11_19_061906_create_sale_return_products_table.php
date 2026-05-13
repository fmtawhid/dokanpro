<?php

use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\Variant;
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
        Schema::create('sale_return_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained((new SaleReturn())->getTable());
            $table->foreignId('product_id')->constrained((new Product())->getTable());
            $table->integer('variant_id')->nullable()->constrained((new Variant())->getTable());
            $table->bigInteger('qty');
            $table->integer('sale_unit_id')->nullable();
            $table->double('net_unit_price')->nullable();
            $table->double('discount')->nullable();
            $table->double('tax_rate')->nullable();
            $table->double('tax')->nullable();
            $table->double('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_products');
    }
};
