<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFeature
{
    /**
     * Feature-to-routes mapping
     */
    private $featureRouteMap = [
        'hrm' => [
            'department.*',
            'employee.*',
            'role.*',
            'attendance.*',
            'holiday.*',
            'payroll.*',
        ],
        'accounting' => [
            'account.*',
            'money.transfer.*',
        ],
        'return' => [
            'sale.return.*',
        ],
        'expense' => [
            'expenseCategory.*',
            'expense.*',
        ],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Skip check if no user
        if (!$user) {
            return $next($request);
        }

        // Get current route name
        $currentRoute = $request->route()?->getName();
        
        if (!$currentRoute) {
            return $next($request);
        }

        // Check if current route requires a feature
        foreach ($this->featureRouteMap as $feature => $routes) {
            foreach ($routes as $pattern) {
                // Convert route pattern to regex-like match
                if ($this->routeMatches($currentRoute, $pattern)) {
                    // Check if user's shop has this feature
                    if (!\hasSubscriptionFeature($feature)) {
                        \Log::warning("Feature access denied: User {$user->email} tried to access {$currentRoute} without {$feature} feature");
                        return redirect()->back()->with('error', "This feature is not available in your current subscription. Please upgrade to access {$feature}.");
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if route name matches a pattern
     */
    private function routeMatches($routeName, $pattern)
    {
        // Handle wildcard patterns like 'department.*'
        if (str_contains($pattern, '*')) {
            $regex = str_replace('*', '.*', $pattern);
            $regex = '/^' . str_replace('.', '\.', $regex) . '$/';
            return preg_match($regex, $routeName);
        }

        // Exact match
        return $routeName === $pattern;
    }
}
