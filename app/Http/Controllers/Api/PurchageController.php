<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\PurchageResource;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\PurchaseRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PurchageController extends Controller
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

        $purchases = PurchaseRepository::query()->where('shop_id', $this->mainShop()->id)
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
                $query->whereHas('supplier', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->latest();

        $total = $purchases->count();

        $purchases = $purchases->when($page && $perPage, function ($query) use ($skip, $perPage) {
            $query->skip($skip)->take($perPage);
        })->get();

        return $this->json(
            'purchases list',
            [
                'total' => $total,
                'purchases' => PurchageResource::collection($purchases),
            ]
        );
    }
    public function purchasePdf(SearchRequest $request)
    {
        $warehouseId = $request->warehouse_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;
        $limit = $request->limit;
        if ($limit == null) {
            $limit = 10;
        }

        $purchases = PurchaseRepository::query()->where('shop_id', $this->mainShop()->id)
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
                $query->whereHas('supplier', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->latest();

        $purchases = $purchases->limit($limit)->get();
        if ($purchases->count() == 0) {
            return $this->json('purchases not found', [], 404);
        }
        
        $generalsettings = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();
        $pdf = true;
        $pdf = Pdf::loadView('purchase.purchasePrint', compact('purchases', 'generalsettings','pdf'));

        $storagePath = storage_path('app/public/invoices');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $pdfPath = $storagePath . '/' . $limit . '_purchase.pdf';
        $pdf->save($pdfPath);
        $path = asset('storage/invoices/' . $limit . '_purchase.pdf');
        return $this->json('purchases pdf', [
            'invoice_pdf_url' => $path,
        ]) ;
    }
}
