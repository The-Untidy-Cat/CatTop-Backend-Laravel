<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
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
        $role = $request->user()->userRole()->whereIn('role_id', [UserRole::ADMIN, UserRole::SELLER])->get();
        if ($role->isEmpty()) {
            return response()->json([
                'code' =>  403,
                'message' => 'Forbidden'
            ], 403);
        }
        return $next($request);
    }
}
