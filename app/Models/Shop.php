<?php

namespace App\Models;

use App\Enums\IsHas;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shopCategory()
    {
        return $this->belongsTo(ShopCategory::class);
    }

    public function generalSettings()
    {
        return $this->hasOne(GeneralSetting::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ShopSubscription::class);
    }

    public function currentSubscriptions()
    {
        return $this->subscriptions()->latest()->where('is_current', IsHas::YES->value)->first();
    }

    public function staffs()
    {
        return $this->hasMany(ShopUser::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class)->withTrashed();
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function shopUser()
    {
        return $this->belongsToMany(User::class, 'shop_users');
    }

    public function businessModules()
    {
        return $this->belongsToMany(BusinessModules::class);
    }
}
