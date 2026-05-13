<?php

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
        if (!Schema::hasColumn('subscription_requests', 'selected_features')) {
            Schema::table('subscription_requests', function (Blueprint $table) {
                $table->json('selected_features')->nullable()->after('status'); // JSON array of feature IDs
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropColumn('selected_features');
        });
    }
};
