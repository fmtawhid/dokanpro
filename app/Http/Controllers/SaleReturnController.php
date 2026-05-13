<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleReturnRequest;
use App\Http\Requests\SearchInvoiceNoRequest;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SaleReturnProductRepository;
use App\Repositories\SaleReturnRepository;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index()
    {
        $saleReturns = SaleReturnRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('saleReturn.index', compact('saleReturns'));
    }
    public function search(SearchInvoiceNoRequest $request)
    {
        $sale = SaleRepository::query()->where('reference_no', $request->invoice_no)->first();
        if (!$sale) {
            return back()->with('error', 'Invalid invoice no! Please Provied a valid invoice no');
        }
        return to_route('sale.return.details', $sale->id);
    }
    public function details(Sale $sale)
    {
        return view('saleReturn.create', compact('sale'));
    }
    public function returnProduct(SaleReturnRequest $request, Sale $sale)
    {
        $productSales = $sale->productSales;
        foreach ($productSales as $productSale) {
            $productSale->product->update([
                'qty' => $productSale->product->qty + $productSale->qty
            ]);
        }
        $sale->productSales()->delete();

        if ($request->products) {
            foreach ($request->products as $saleProduct) {
                $product = ProductRepository::find($saleProduct['id']);

                $productTax = 0;
                $price = $saleProduct['netUnitCost'] ? $saleProduct['netUnitCost'] : $product->price;

                if ($product->tax) {
                    $productTax = $price * $product->tax->rate / 100;
                }
                $product->update([
                    'qty' => $product->qty - $saleProduct['qty']
                ]);

                ProductSale::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'net_unit_price' => $price,
                    'qty' => $saleProduct['qty'],
                    'discount' => 0,
                    'tax_rate' => $product->tax?->rate,
                    'tax' => $productTax,
                    'total' => ($price + $productTax) * $saleProduct['qty'],
                ]);
            }
            $saleReturn = SaleReturnRepository::storeByRequest($request, $sale);
            SaleReturnProductRepository::storeByRequest($request, $saleReturn, $sale);
            $sale->update([
                'total_discount' => $request->total_discount,
                'total_tax' => $request->total_tax,
                'total_qty' => $request->total_qty,
                'item' => $request->item,
                'total_price' => $request->total_price,
                'order_tax' => $request->order_tax,
                'grand_total' => $request->grand_total,
                'sale_note' => $request->note,
            ]);
        } else {
            $sale->delete();
        }

        return to_route('sale.return.index')->with('success', 'Product successfully returned!');
    }
}
