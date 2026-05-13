<?php

use App\Models\Customer;
use App\Models\Media;
use App\Models\Shop;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->string('reference_no');
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->foreignId('customer_id')->nullable()->constrained((new Customer())->getTable());
            $table->foreignId('warehouse_id')->nullable()->constrained((new Warehouse())->getTable());
            $table->integer('item');
            $table->double('total_qty');
            $table->double('total_discount');
            $table->double('total_tax');
            $table->double('total_price');
            $table->double('grand_total');
            $table->double('order_tax_rate')->nullable();
            $table->double('order_tax')->nullable();
            $table->double('order_discount')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->double('coupon_discount')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->integer('sale_status');
            $table->integer('payment_status');
            $table->foreignId('document_id')->nullable()->constrained((new Media())->getTable());
            $table->double('paid_amount')->nullable();
            $table->text('sale_note')->nullable();
            $table->text('staff_note')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('sales');
    }
}
