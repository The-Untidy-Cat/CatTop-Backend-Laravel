<?php

namespace App\Http\Controllers\Api;

use Closure;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class XSRFController extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $xsrf_token = $request->cookie('XSRF-TOKEN');
        if ($xsrf_token !== null)
            $request->headers->set("X-XSRF-TOKEN", $xsrf_token);
        return $next($request);
    }
}
