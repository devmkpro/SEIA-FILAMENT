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

        if ($request->cookie('SHID')) {
            $school = School::where('code', $request->cookie('SHID'))->first();
            if (!$school) {
                Cookie::queue(Cookie::forget('SHID'));
            }

            $userHasAdminRole = $request->user()->isAdmin();
            $userHasSchool = $request->user()->schools()->count() > 0 && $request->user()->schools()->where('school_id', $school->id)->exists();

            if (!$userHasAdminRole && !$userHasSchool) {
                Cookie::queue(Cookie::forget('SHID'));
            }
        }

        return $next($request);
    }
}
