<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $middleware = new CheckSchoolCookieForPages();
        $isValid = $middleware->handle(request(), function ($request) {
            return false;
        });

        if (!$isValid) {
            Cookie::queue(Cookie::forget('SHID'));
            return Redirect::back()->withErrors('Escola não encontrada para gerenciamento.');
        }

        return $next($request);
    }
}
