<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\Variant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained((new Sale())->getTable());
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sales');
    }
}
