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
        Schema::create('purchase_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->constrained();
            $table->string('name');
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('qty');
            $table->integer('sale_qty');
            $table->date('expire_date')->nullable();
            $table->date('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_batches');
    }
};
