<?php

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->double('amount')->default(0);
            $table->double('min_amount')->nullable();
            $table->double('max_amount')->nullable();
            $table->integer('qty')->default(0);
            $table->date('expired_at')->nullable();
            $table->integer('used')->default(0);
            $table->timestamps();;
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
