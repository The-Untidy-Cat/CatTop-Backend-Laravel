<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()->userRole()->whereIn('role_id', [1, 2, 4, 5, 6, 7, 8, 9])->get();
        if ($role->isEmpty()) {
            return response()->json([
                'status' => 403,
                'message' => 'Forbidden'
            ], 403);
        }
        return $next($request);
    }
}
