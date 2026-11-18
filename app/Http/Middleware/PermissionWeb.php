<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionWeb
{
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (Auth::guest()) {
            return redirect('/login');
        }
        if ($permission === null || trim($permission) === '' || ! $request->user()?->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
