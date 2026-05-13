<?php

use App\Models\Media;
use App\Models\Shop;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->string('site_title');
            $table->foreignId('logo_id')->nullable()->constrained((new Media())->getTable());
            $table->foreignId('small_logo_id')->nullable()->constrained((new Media())->getTable());
            $table->foreignId('fav_id')->nullable()->constrained((new Media())->getTable());
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('currency_position')->nullable();
            $table->string('date_with_time');
            $table->string('date_format')->nullable();
            $table->string('developed_by')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('direction')->nullable();
            $table->string('lang')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
}
