<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Http\Middleware\EncryptCookies;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Auth\AuthenticationException as AuthenticationException;

class AuthByCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->cookie('auth_token') !== null) {
            $request->headers->set('Authorization', sprintf('%s %s', 'Bearer', $request->cookie('auth_token')));
        } else {
            throw new AuthenticationException('No certification found');
        }
        return $next($request);
    }
}