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
        Schema::table('recovery_password_codes', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('token')->nullable()->after('code');
            $table->string('email')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recovery_password_codes', function (Blueprint $table) {
            $table->string('status')->nullable()->after('code');
            $table->dropColumn('token');
            $table->dropColumn('email');
        });
    }
};
