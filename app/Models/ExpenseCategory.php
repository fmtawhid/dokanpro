<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $guarded = ['id'];

    public function expense() {
    	return $this->hasMany(Expense::class)->withTrashed();
    }
}
