<?php

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Variant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer("Variant_id")->nullable();
            $table->bigInteger('qty');
            $table->integer('purchase_unit_id');
            $table->double('net_unit_cost');
            $table->double('discount');
            $table->double('tax_rate')->nullable();
            $table->double('tax');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchases');
    }
}
