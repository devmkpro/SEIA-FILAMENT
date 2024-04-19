<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolCookieForPages
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): bool
    {
        if (!$request->cookie('SHID') || !$request->user()) {
            return true;
        }

        $school = School::where('code', $request->cookie('SHID'))->first();
        if (!$school) {
            Cookie::queue(Cookie::forget('SHID'));
            return false;
        } else if ($school->active == 'Inativa' && !$request->user()->isAdmin()) {
            Cookie::queue(Cookie::forget('SHID'));
            return false;
        }

        $userHasAdminRole = $request->user()->isAdmin(); // retorna collection
        if (!$userHasAdminRole) {
            $userHasSchool = $request->user()->schools()->count() > 0 && $request->user()->schools()->where('school_id', $school->id)->exists();
            if (!$userHasAdminRole && !$userHasSchool) {
                Cookie::queue(Cookie::forget('SHID'));
                return false;
            }
        }

        return true;
    }
}
