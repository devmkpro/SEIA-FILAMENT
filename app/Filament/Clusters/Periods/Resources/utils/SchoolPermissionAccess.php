<?php

namespace App\Filament\Clusters\Periods\Resources\utils;

use App\Http\Middleware\CheckSchoolCookieForPages;
use App\Http\Middleware\RequireSchoolCookie;
use App\Models\SchoolYear;

class SchoolPermissionAccess 
{
  public static function canAccess(): bool
  {

      $isValid = (new RequireSchoolCookie())->handle(request(), function ($request) {
          return false;
      });

      if (!$isValid) {
          return false;
      }

      $isValid = (new CheckSchoolCookieForPages())->handle(request(), function ($request) {
          return false;
      });

      if (!$isValid) {
          return false;
      }

      return true;
  }
}