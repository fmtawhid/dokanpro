<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\AdminSaleResource;
use App\Models\Sale;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\SaleRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(SearchRequest $request)
    {
        $warehouseId = $request->warehouse_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $sales = SaleRepository::query()->where('type', 'Sales')->where('shop_id', $this->mainShop()->id)
            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate)->format('Y-m-d'));
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate)->format('Y-m-d'));
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->latest();

        $total = $sales->count();

        $sales = $sales->when($page && $perPage, function ($query) use ($skip, $perPage) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json("sales list", [
            'total' => $total,
            'sales' => AdminSaleResource::collection($sales),
        ]);
    }
    public function salePdf(SearchRequest $request)
    {
        $warehouseId = $request->warehouse_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;
        $limit = $request->limit;
        if ($limit == null) {
            $limit = 10;
        }

        $sales = SaleRepository::query()->where('type', 'Sales')->where('shop_id', $this->mainShop()->id)
            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate)->format('Y-m-d'));
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate)->format('Y-m-d'));
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->latest();

        $sales = $sales->limit($limit)->get();
        if ($sales->count() == 0) {
            return $this->json('sales not found', [], 404);
        }

        $generalsettings = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();
        $pdf = true;
        $pdf = Pdf::loadView('sale.salePrint', compact('sales', 'generalsettings','pdf'));

        $storagePath = storage_path('app/public/invoices');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $pdfPath = $storagePath . '/' . $limit . '_sale.pdf';
        $pdf->save($pdfPath);
        $path = asset('storage/invoices/' . $limit . '_sale.pdf');
        return $this->json('sales pdf', [
            'invoice_pdf_url' => $path,
        ]) ;
    }

    public function paymentSuccess(Sale $sale)
    {
        $sale->update([
            'payment_status' => 3,
            'type' => 'Sales'
        ]);
        return true;
    }

    public function paymentFailure(Sale $sale)
    {
        $sale->update(['payment_status' => 2]);
        return true;
    }
}
