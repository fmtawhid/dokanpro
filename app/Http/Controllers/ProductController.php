<?php

namespace App\Http\Controllers;

use App\Enums\BarcodeSymbology;
use App\Enums\ProductTypes;
use App\Enums\TaxMethods;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\ProductWarehouseRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UnitRepository;
use App\Repositories\VariantRepository;
use App\Repositories\WarehouseRepository;
use Keygen\Keygen;

class ProductController extends Controller
{
    public function index()
    {
        $status = request()->status;
        $products = ProductRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->when($status, function ($query) use ($status) {
            $query->where('type', $status);
        })->get();

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $categories = CategoryRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $brands = BrandRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $units = UnitRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $warehouses = WarehouseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $taxs = TaxRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $barcodeSymbologyes = BarcodeSymbology::cases();
        $productTypes = ProductTypes::cases();
        $taxMethods = TaxMethods::cases();
        return view('product.create', compact('categories', 'brands', 'units', 'warehouses', 'taxs', 'barcodeSymbologyes', 'productTypes', 'taxMethods'));
    }

    public function store(ProductRequest $request)
    {
        $subscription = $this->mainShop()?->currentSubscriptions()?->subscription;
        $existsProduct = $this->mainShop()->products;
        if ($this->mainShop()->is_lifetime == 0 && $existsProduct->count() >= $subscription->product_limit ?? 0) {
            return back()->withError('You have extend your limit');
        }
        $product = ProductRepository::storeByRequest($request);

        if ($request->variant_name) { //If not empty variant
            $productVariant = [];
            foreach ($request->variant_name as $key => $variant) {
                $variantId = VariantRepository::storyByRequest($variant);
                $productVariant[] = [
                    'variant_id' => $variantId->id,
                    'item_code' => $request->item_code[$key],
                    'additional_price' => $request->additional_price[$key],
                ];
            }

            $product->productVariant()->attach($productVariant);
        }

        return to_route('product.index')->with('success', 'Product is created successfully!');
    }

    public function edit(Product $product)
    {
        $productList = json_decode($product->product_list);
        $qtyList = json_decode($product->qty_list);
        $priceList = json_decode($product->price_list);
        $compoProducts = [];
        if ($productList) {
            foreach ($productList as $key => $productCombo) {
                $compoProducts[] = [
                    'product' =>  ProductRepository::find($productCombo),
                    'qty' => $qtyList[$key],
                    'price' => $priceList[$key]
                ];
            }
        }

        $categories = CategoryRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $brands = BrandRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $units = UnitRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $warehouses = WarehouseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $taxs = TaxRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $barcodeSymbologyes = BarcodeSymbology::cases();
        $productTypes = ProductTypes::cases();
        $taxMethods = TaxMethods::cases();
        return view('product.edit', compact('categories', 'brands', 'units', 'taxs', 'warehouses', 'product', 'compoProducts', 'barcodeSymbologyes', 'productTypes', 'taxMethods'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        ProductRepository::updateByRequest($request, $product);
        if ($request->variant_name) {
            if ($request->variant_id) {
                $productVariant = [];
                foreach ($request->variant_name as $key => $variantName) {
                    $variantId = array_key_exists($key, $request->variant_id) ? $request->variant_id[$key] : null;
                    if ($variantId) {
                        $variant = VariantRepository::query()->where('id', $variantId)->first();
                        $variant->update([
                            'name' => $variantName
                        ]);
                    } else {
                        $variant = VariantRepository::storyByRequest($variantName);
                    }
                    $productVariant[$variantId] = [
                        'variant_id' => $variant->id,
                        'item_code' => $request->item_code[$key],
                        'additional_price' => $request->additional_price[$key],
                    ];
                }
                $product->productVariant()->sync($productVariant);
            } else {
                $productVariant = [];
                foreach ($request->variant_name as $key => $variant) {
                    $variantId = VariantRepository::storyByRequest($variant);
                    $productVariant[] = [
                        'variant_id' => $variantId->id,
                        'item_code' => $request->item_code[$key],
                        'additional_price' => $request->additional_price[$key],
                    ];
                }
                $product->productVariant()->attach($productVariant);
            }
        }

        return back()->with('success', 'Product is updated successfully!');
    }
    public function destroy(Product $product)
    {
        $productWarehouses = ProductWarehouseRepository::query()->where('product_id', $product->id)->get();
        $productVariants = ProductVariantRepository::query()->where('product_id', $product->id)->get();
        foreach ($productWarehouses as $productWarehouse) {
            $productWarehouse->delete();
        }
        foreach ($productVariants as $productVariant) {
            $productVariant->delete();
        }
        $product->delete();
        return back()->with('success', 'Product is deleted successfully');
    }

    public function trash()
    {
        $products = ProductRepository::query()->onlyTrashed()->where('shop_id', $this->mainShop()->id)->orderByDesc('deleted_at')->get();
        return view('product.trash', compact('products'));
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            return back()->with('error', 'Product not found');
        }
        $product->forceDelete();
        return back()->with('success', 'Product permanently deleted');
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            return back()->with('error', 'Product not found');
        }
        $product->restore();
        return back()->with('success', 'Product restored successfully');
    }

    public function generateCode()
    {
        $mainShop = mainShop();
        if ($mainShop) {
            $generalSettings = GeneralSettingRepository::query()->where('shop_id', $mainShop->id)->first();
        }
        $digits = $generalSettings->barcode_digits ?? 8;
        $id = Keygen::numeric($digits)->generate();
        return $id;
    }
    //products search
    public function productSearch(Request $request)
    {
        $products = ProductRepository::search($request->search, $request->category_id, $request->brand_id)->get();
        return $this->json('message', [
            'products' => ProductResource::collection($products)
        ]);
    }
    // Get signle product
    public function productDetails(Request $request)
    {
        $product = ProductRepository::find($request->id);
        return $this->json('message', [
            'product' => ProductResource::make($product)
        ]);
    }

    public function getData($id)
    {
        $data = Product::select('name', 'code')->where('id', $id)->get();
        return $data[0];
    }

    public function productWithoutVariant()
    {
        return ProductRepository::query()->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return Product::join('product_variants', 'products.id', 'product_variants.product_id')
            ->whereNotNull('is_variant')
            ->select('products.id', 'products.name', 'product_variants.item_code')
            ->orderBy('position')->get();
    }


    public function saleUnit(Request $request)
    {
        $units = UnitRepository::query()->where("base_unit_id", $request->id)->orWhere('id', $request->id)->pluck('name', 'id');
        return $this->json('message', [
            'unit' => $units,
        ]);
    }

    public function barcodeGenerate(Request $request)
    {
        $this->validate($request, [
            'product_ids' => 'required|array',
            'size' => 'required|in:row,single',
        ]);
        $size = $request->size;
        $products = ProductRepository::query()->whereIn('id', $request->product_ids)->get();
        $barcodeDetails = [];
        foreach ($products as $key => $product) {
            $barcodeDetails[] = [
                'name' => $request->name ? $product->name : null,
                'price' => $request->price ? $product->price : null,
                'promotional_price' => $request->promo_price ? $product->promotion_price : null,
                'qty' => $request->qtys[$key],
                'code' =>  $product->code
            ];
        }

        return view('product.print_barcode', compact('barcodeDetails', 'size'));
    }

    public function printBarcode()
    {
        return view('product.barcode');
    }

    public function productDownloadSample()
    {
        return response()->download(public_path('import/sample_products.csv'));
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|file|mimes:csv'
        ]);
        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file));

        $storeInvalidIndex = 0;
        $notExistsCategory = [];
        $notExistsBrand = [];
        $notExistsUnit = [];
        foreach ($csvData as $key => $row) {
            if ($key > 0) {
                try {
                    $brand = BrandRepository::query()->where('title', $row[3])->first();
                    $category = CategoryRepository::query()->where('name', $row[4])->first();
                    $unit = UnitRepository::query()->where('name', $row[5])->first();

                    $thumbnail = Media::factory()->create();
                    Product::create([
                        'created_by' => auth()->id(),
                        'shop_id' => $this->mainShop()->id,
                        'name' => $row[0],
                        'code' => $row[1],
                        'type' => ucfirst($row[2]),
                        'barcode_symbology' => BarcodeSymbology::CODE_128->value,
                        'brand_id' => $brand?->id,
                        'category_id' => $category?->id,
                        'unit_id' => $unit?->id,
                        'purchase_unit_id' => $unit?->id,
                        'sale_unit_id' => $unit?->id,
                        'cost' => $row[6],
                        'price' => $row[7],
                        'product_details' => $row[8],
                        'thumbnail_id' => $thumbnail->id,
                    ]);
                } catch (\Exception $e) {
                    $storeInvalidIndex++;
                    $notExistsBrand[] = $row[3];
                    $notExistsCategory[] = $row[4];
                    $notExistsUnit[] = $row[5];
                }
            }
        }

        $message = null;
        if (!empty($notExistsBrand)) {
            $errorbrands = implode(',', $notExistsBrand);
            $message .= "Brands not found: $errorbrands ";
        }
        if (!empty($notExistsCategory)) {
            $errorCategories = implode(',', $notExistsCategory);
            $message .= "<br>Categories not found: $errorCategories ";
        }
        if (!empty($notExistsUnit)) {
            $errorUnits = implode(',', $notExistsUnit);
            $message .= "<br>Units not found: $errorUnits ";
        }
        if ($storeInvalidIndex + 1 == count($csvData) || $message) {
            return back()->with('error',   $message ?? 'Please provide valid data in the csv file!');
        }
        return back()->with('success', 'Products import successfully');
    }
}
