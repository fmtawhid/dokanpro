<?php

namespace App\Models;

use App\Enums\BarcodeSymbology;
use App\Enums\DiscountType;
use App\Enums\ProductTypes;
use App\Enums\TaxMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'discount_type' => DiscountType::class,
        'type' => ProductTypes::class,
        'barcode_symbology' => BarcodeSymbology::class,
        'tax_method' => TaxMethods::class,
    ];

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function productVariant()
    {
        return $this->belongsToMany(Variant::class, 'product_variants')->withPivot('item_code', 'additional_price');
    }
    //get data from product variant pivot table
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productsWarehouse()
    {
        return $this->belongsToMany(Variant::class, (new ProductVariant())->getTable(), 'variant_id')->withPivot('product_id', 'item_code', 'additional_price');
    }

    public function productWarehouse()
    {
        return $this->belongsToMany(Warehouse::class, (new ProductWarehouse())->getTable())->withPivot('product_id', 'price', 'qty')->withTrashed();
    }

    public function purchaseProducts()
    {
        return $this->hasMany(ProductPurchase::class, 'product_id');
    }

    public function saleProducts()
    {
        return $this->hasMany(ProductSale::class, 'product_id');
    }

    public function saleReturnProducts()
    {
        return $this->hasMany(SaleReturnProduct::class, 'product_id');
    }

    public function serialNumbers()
    {
        return $this->hasMany(PurchaseProductSerialNumber::class, 'product_id');
    }

    public function scopeActiveStandard($query)
    {
        return $query->where([
            ['type', 'standard']
        ]);
    }

    public function scopeActiveFeatured($query)
    {
        return $query->where([
            ['is_featured', 1]
        ]);
    }
}
