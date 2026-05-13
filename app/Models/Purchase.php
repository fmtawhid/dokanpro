<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, (new ProductPurchase())->getTable())->withPivot('qty', 'recieved', 'purchase_unit_id', 'net_unit_cost', 'discount', 'tax_rate', 'tax', 'total')->withTrashed();
    }

    public function purchaseProducts()
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class, 'document_id');
    }

    public function purchaseBatches()
    {
        return $this->hasMany(PurchaseBatch::class);
    }

    public function purchaseProductsSerialNumbers()
    {
        return $this->hasMany(PurchaseProductSerialNumber::class);
    }
}
