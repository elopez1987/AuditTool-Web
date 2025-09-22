<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PasswordChangeThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Clave para el rate limiting
        $throttleKey = 'password_change_attempts_' . $user->id;
        $attempts = Cache::get($throttleKey, 0);

        // Límite: máximo 3 intentos por hora
        $maxAttempts = 3;
        $decayMinutes = 60;

        if ($attempts >= $maxAttempts) {
            Log::warning('Usuario bloqueado por exceder intentos de cambio de contraseña', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'attempts' => $attempts,
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()
                ->withErrors(['throttle' => 'Demasiados intentos de cambio de contraseña. Intente nuevamente en 1 hora.'])
                ->withInput();
        }

        // Si es un intento de cambio de contraseña, incrementar contador
        if ($request->has('password') && $request->filled('password')) {
            Cache::put($throttleKey, $attempts + 1, now()->addMinutes($decayMinutes));
        }

        $response = $next($request);

        // Si el cambio fue exitoso, limpiar el contador
        if ($response->getStatusCode() === 302 &&
            session()->has('info') &&
            strpos(session('info'), 'actualizado') !== false) {
            Cache::forget($throttleKey);
        }

        return $response;
    }
}
