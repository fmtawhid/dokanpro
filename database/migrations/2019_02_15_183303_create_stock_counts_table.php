<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Shop;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->string('reference_no');
            $table->foreignId('warehouse_id')->constrained((new Warehouse())->getTable());
            $table->string('category_id')->nullable();
            $table->string('brand_id')->nullable();
            $table->string('type');
            $table->string('initial_file')->nullable();
            $table->string('final_file')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_adjusted');
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
        Schema::dropIfExists('stock_counts');
    }
}
