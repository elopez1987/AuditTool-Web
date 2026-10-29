<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth0\Laravel\Guards\AuthenticationGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        /** @var AuthenticationGuard $guard */
        $guard = Auth::guard('auth0-session');
        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $logoutUrl = $guard->sdk()->authentication()->getLogoutLink(route('login'));

        return redirect()->away($logoutUrl)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
