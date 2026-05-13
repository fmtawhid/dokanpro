<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{

    public function index()
    {
        $brands = BrandRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('brand.index', compact('brands'));
    }

    public function store(BrandRequest $request)
    {
        BrandRepository::storeByRequest($request);
        return back()->with('success', 'Brand is created successfully!');
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        // Verify brand belongs to current shop
        if ($brand->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to update this brand!');
        }
        BrandRepository::updateByRequest($request, $brand);
        return back()->with('success', 'Brand is updated successfully!');
    }

    public function delete(Brand $brand)
    {
        // Verify brand belongs to current shop
        if ($brand->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this brand!');
        }
        $brand->delete();
        return back()->with('success', 'Brand is deleted successfully!');
    }

    public function trash()
    {
        $brands = BrandRepository::query()->onlyTrashed()->where('shop_id', $this->mainShop()->id)->orderByDesc('deleted_at')->get();
        return view('brand.trash', compact('brands'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if (!$brand) {
            return back()->with('error', 'Brand not found');
        }
        if ($brand->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to restore this brand!');
        }
        $brand->restore();
        return back()->with('success', 'Brand restored successfully');
    }

    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if (!$brand) {
            return back()->with('error', 'Brand not found');
        }
        if ($brand->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this brand!');
        }
        
        // Check if brand has any products
        $productCount = $brand->product()->count();
        if ($productCount > 0) {
            return back()->with('error', 'Cannot delete this brand! It has ' . $productCount . ' product(s) associated with it. Please delete or change the brand of those products first.');
        }
        
        $brand->forceDelete();
        return back()->with('success', 'Brand permanently deleted');
    }
}