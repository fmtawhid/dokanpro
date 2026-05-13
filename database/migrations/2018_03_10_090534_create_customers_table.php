<?php

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Customer())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->foreignId('customer_group_id')->nullable()->constrained((new CustomerGroup())->getTable());
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->double('deposit')->nullable();
            $table->double('expense')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists((new Customer())->getTable());
    }
}
