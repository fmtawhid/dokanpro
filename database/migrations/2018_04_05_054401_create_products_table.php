<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\User;
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
        Schema::create((new Product())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->string('barcode_symbology');
            $table->foreignIdFor(Brand::class)->constrained();
            $table->foreignId('category_id')->constrained((new Category())->getTable());
            $table->foreignId('unit_id')->nullable()->constrained((new Unit())->getTable());
            $table->integer('purchase_unit_id')->nullable();
            $table->integer('sale_unit_id')->nullable();
            $table->double('cost')->nullable();
            $table->double('price');
            $table->double('qty')->nullable();
            $table->double('alert_quantity')->nullable();
            $table->tinyInteger('is_promotion_price')->nullable();
            $table->double('promotion_price')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();
            $table->foreignId('tax_id')->nullable()->constrained((new Tax())->getTable());
            $table->string('tax_method')->nullable();
            $table->foreignId('thumbnail_id')->constrained((new Media())->getTable());
            $table->tinyInteger('is_featured')->nullable();
            $table->text('product_details')->nullable();
            $table->string('product_list')->nullable();
            $table->string('qty_list')->nullable();
            $table->string('price_list')->nullable();
            $table->tinyInteger('is_batch')->nullable();
            $table->tinyInteger('is_variant')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
