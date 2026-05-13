<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionExpireCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $subscription = $this->mainShop()->currentSubscriptions();

        if (!$this->mainShop()->is_lifetime && (!$subscription || $subscription->expired_at <= date('Y-m-d'))) {
            $user = auth()->user();
            if ($user->getRoleNames()[0] === 'store') {
                Auth::logout();
            }
            return to_route('subscription.purchase.index')->with('error', 'Sorry, Your subscription has been expired');
        }

        return $next($request);
    }

    private function mainShop()
    {
        $user = auth()->user();
        $mainShop = $user->shopUser->first();

        return match ($mainShop) {
            null => $user->shop,
            default => $mainShop
        };
    }
}
