<?php

use App\Models\Account;
use App\Models\Employee;
use App\Models\Shop;
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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained((new User())->getTable());
            $table->foreignId('shop_id')->nullable()->constrained((new Shop())->getTable());
            $table->date('date');
            $table->foreignId('employee_id')->constrained((new Employee())->getTable());
            $table->foreignId('account_id')->constrained((new Account())->getTable());
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
