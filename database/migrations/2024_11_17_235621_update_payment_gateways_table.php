<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Media;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->dropColumn(['value', 'status']);
            $table->string('title')->after('name');
            $table->foreignIdFor(Media::class)
                ->nullable()
                ->after('title')
                ->constrained()
                ->nullOnDelete();
            $table->string('mode')
                ->default('test')
                ->after('title')
                ->comment('test or live');
            $table->string('alias')
                ->nullable()
                ->after('mode')
                ->comment('controller namespace');
            $table->json('config')->nullable()->after('alias');
            $table->boolean('is_active')->default(false)->after('config');
            $table->unsignedBigInteger('shop_id')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->longText('value')->after('title');
            $table->string('status')->after('value');

            $table->dropColumn(['title', 'media_id', 'mode', 'alias', 'config', 'is_active', 'shop_id']);
            $table->dropForeign(['media_id']);
        });
    }
};
