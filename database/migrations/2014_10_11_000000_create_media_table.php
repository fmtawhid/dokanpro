<?php

use App\Models\Media;
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
        Schema::create((new Media())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('extension')->nullable();
            $table->string('src');
            $table->string('path')->nullable();
            $table->string('type')->default('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Media())->getTable());
    }
};
