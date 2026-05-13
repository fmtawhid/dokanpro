<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GeneralSettingRepository;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        $settings = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();

        $symbol = $settings->defaultCurrency->symbol ?? '$';
        $currencyPosition = $settings->currency_position?->value ?? 'Prefix';

        return $this->json('General Settings', [
            'currency_position' => $currencyPosition,
            'symbol' => $symbol
        ]);

    }
}
