<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class PermissionWeb
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }
        if (! $request->user()->can($permission)) {
            abort(403);
        }
        return $next($request);
    }
}
