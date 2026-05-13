<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function json(string $message = null, $data = [], $statusCode = 200, array $headers = [], $options = JSON_PRESERVE_ZERO_FRACTION)
    {
        $content = [];
        if ($message) {
            $content['message'] = $message;
        }
        if (!empty($data)) {
            $content['data'] = $data;
        }
        return response()->json($content, $statusCode, $headers, $options);
    }

    protected function mainShop()
    {
        $user = auth()->user();
        $mainShop = $user->shopUser->first();


        return match ($mainShop) {
            null => $user->shop,
            default => $mainShop
        };
    }
}
