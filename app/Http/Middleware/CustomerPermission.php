<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()->userRole()->whereIn('role_id', [3])->get();
        if ($role->isEmpty()) {
            return response()->json([
                'code' =>  403,
                'message' => 'Forbidden'
            ], 403);
        }
        return $next($request);
    }
}
