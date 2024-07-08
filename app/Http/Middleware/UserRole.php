<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()->role_id;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return response()->view('errors.check-permission');
    }
}
