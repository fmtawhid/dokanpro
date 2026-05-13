<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Exception;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = SupplierRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(SupplierRequest $request)
    {
        SupplierRepository::storeByRequest($request);
        return to_route('supplier.index')->with('success', 'Supplier is created successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        SupplierRepository::updateByRequest($request, $supplier);
        return to_route('supplier.index')->with('success', 'Supplier is update successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this supplier!');
        }
        $supplier->delete();
        return back()->with('success', 'Supplier is deleted successfully!');
    }

    public function trash()
    {
        $suppliers = SupplierRepository::query()->onlyTrashed()->where('shop_id', $this->mainShop()->id)->orderByDesc('deleted_at')->get();
        return view('supplier.trash', compact('suppliers'));
    }

    public function restore($id)
    {
        $supplier = Supplier::onlyTrashed()->find($id);
        if (!$supplier) {
            return back()->with('error', 'Supplier not found');
        }
        if ($supplier->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to restore this supplier!');
        }
        $supplier->restore();
        return back()->with('success', 'Supplier restored successfully');
    }

    public function forceDelete($id)
    {
        $supplier = Supplier::onlyTrashed()->find($id);
        if (!$supplier) {
            return back()->with('error', 'Supplier not found');
        }
        if ($supplier->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this supplier!');
        }
        
        // Check if supplier has any products
        $productCount = $supplier->product()->count();
        if ($productCount > 0) {
            return back()->with('error', 'Cannot delete this supplier! It has ' . $productCount . ' product(s) associated with it.');
        }
        
        $supplier->forceDelete();
        return back()->with('success', 'Supplier permanently deleted');
    }

    public function downloadSupplierSample()
    {
        return response()->download(public_path('import/sample_supplier.csv'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file));
        try {
            foreach ($csvData as $key => $row) {
                if ($key > 0) {
                    SupplierRepository::create([
                        'created_by' => auth()->id(),
                        'shop_id' => $this->mainShop()->id,
                        'name' => $row[0],
                        'company_name' => $row[1],
                        'vat_number' => $row[2],
                        'email' => $row[3],
                        'phone_number' => $row[4],
                        'address' => $row[5],
                        'city' => $row[6],
                        'state' => $row[7],
                        'postal_code' => $row[8],
                        'country' => $row[9],
                    ]);
                }
            }
            return back()->with('success', 'Supplier import successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Please provide valid data in the csv file!');
        }
    }
}
