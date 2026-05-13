<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SingleSaleResource;
use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index()
    {
        $request = request();
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;
        $drafts = SaleRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->where('type', 'Draft');

        $total = $drafts->count();

        $drafts = $drafts->when($page && $perPage, function ($query) use ($skip, $perPage) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json("Draft list", [
            'total' => $total,
            'drafts' => SingleSaleResource::collection($drafts),
        ]);
    }

    public function delete(Sale $sale)
    {
        foreach ($sale->productSales as $draftProduct) {
            $draftProduct->product->update(['qty' => $draftProduct->product->qty + $draftProduct->qty]);
        }
        $sale->productSales()->delete();
        $sale->delete();

        return $this->json('Draft deleted successfully');
    }
}
