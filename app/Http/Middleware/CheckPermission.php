<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Try to get route - it might not be resolved yet in middleware
        // Try multiple methods to find the route name
        $requestRoute = request()->route()?->getName();
        
        // If route name is still null, try matching the request
        if (!$requestRoute) {
            try {
                $matchedRoute = \Route::getRoutes()->match($request);
                $requestRoute = $matchedRoute->getName();
            } catch (\Exception $e) {
                \Log::warning("CheckPermission: Could not match route. Error: " . $e->getMessage());
                $requestRoute = null;
            }
        }
        
        // Log all requests for debugging
        \Log::debug("CheckPermission middleware - User: {$user?->email}, Route: {$requestRoute}");
        
        // Check if user exists
        if (!$user) {
            \Log::warning("CheckPermission: User not authenticated");
            return back()->with('error', 'User not authenticated');
        }
        
        // Check if user has any roles assigned
        if (!$user->roles || $user->roles->isEmpty()) {
            \Log::warning("CheckPermission: User {$user->email} (ID: {$user->id}) has no roles assigned");
            return back()->with('error', 'User role not configured. Please contact administrator.');
        }
        
        // Get the user's first role
        $userRole = $user->roles->first();
        if (!$userRole) {
            \Log::warning("CheckPermission: User {$user->email} - role not found");
            return back()->with('error', 'User role not found');
        }
        
        // Get all permissions for the user's role
        $allPermissions = $userRole->getPermissionNames()->toArray();
        
        \Log::debug("CheckPermission: User {$user->email} with role '{$userRole->name}' - Total permissions: " . count($allPermissions));
        \Log::debug("CheckPermission: Checking route: {$requestRoute}");
        
        // Check if the current route is in the user's permissions
        if (in_array($requestRoute, $allPermissions)) {
            \Log::debug("CheckPermission: Permission GRANTED for {$user->email} on route {$requestRoute}");
            return $next($request);
        }

        \Log::warning("CheckPermission: Permission DENIED - User {$user->email} attempted to access unauthorized route: {$requestRoute}. Available permissions: " . implode(', ', array_slice($allPermissions, 0, 5)) . "...");
        return back()->with('error', 'Sorry, You have no permission to access this page');
    }
}
