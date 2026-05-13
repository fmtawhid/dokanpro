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
        Schema::table('purchases', function (Blueprint $table) {
            $table->double('total_discount', 25, 2)->change();
            $table->double('total_tax', 25, 2)->change();
            $table->double('total_cost', 25, 2)->change();
            $table->double('order_tax_rate', 25, 2)->change()->nullable();
            $table->double('order_tax', 25, 2)->change()->nullable();
            $table->double('order_discount', 25, 2)->change()->nullable();
            $table->double('shipping_cost', 25, 2)->change()->nullable();
            $table->double('grand_total', 25, 2)->change();
            $table->double('paid_amount', 25, 2)->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
