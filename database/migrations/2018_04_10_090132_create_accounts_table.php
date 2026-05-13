<?php

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->string('account_no');
            $table->string('name');
            $table->double('initial_balance')->nullable();
            $table->double('total_balance');
            $table->text('note')->nullable();
            $table->boolean('is_default')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
