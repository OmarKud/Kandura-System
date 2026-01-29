<?php

namespace App\Http\Controllers\Web\Superadmin;

use App\Http\Controllers\Controller;

class BaseSuperadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // superadmin only (by role name OR role_id = 4)
        $this->middleware(function ($request, $next) {
            $u = auth()->user();
            if (!$u || (!$u->hasRole('superadmin') && (int)$u->role_id !== 4)) {
                abort(403);
            }
            return $next($request);
        });
    }
}
