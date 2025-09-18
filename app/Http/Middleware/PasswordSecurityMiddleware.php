<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Rate Limiting
        if ($this->isRateLimited($request, $user)) {
            return redirect()->back()
                ->withErrors(['throttle' => 'Demasiados intentos de cambio de contraseña. Intente nuevamente en 1 hora.'])
                ->withInput();
        }

        // 2. Validación de contraseña actual (si se proporciona)
        if ($request->has('current_password') && $request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                $this->logFailedAttempt($user, $request, 'Contraseña actual incorrecta');
                
                return redirect()->back()
                    ->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                    ->withInput();
            }
        }

        // 3. Verificación de reutilización de contraseña
        if ($request->has('password') && $request->filled('password')) {
            if (Hash::check($request->password, $user->password)) {
                $this->logFailedAttempt($user, $request, 'Intento de reutilizar contraseña actual');
                
                return redirect()->back()
                    ->withErrors(['password' => 'La nueva contraseña debe ser diferente a la actual.'])
                    ->withInput();
            }
        }

        // 4. Verificación de horarios permitidos (opcional)
        if (!$this->isAllowedTime()) {
            Log::warning('Intento de cambio de contraseña fuera de horario permitido', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'time' => now()
            ]);
            
            return redirect()->back()
                ->withErrors(['schedule' => 'Los cambios de contraseña solo están permitidos entre 6:00 AM y 10:00 PM.'])
                ->withInput();
        }

        return $next($request);
    }

    /**
     * Verificar si el usuario está limitado por rate limiting
     *
     * @param Request $request
     * @param $user
     * @return bool
     */
    private function isRateLimited(Request $request, $user): bool
    {
        $throttleKey = 'password_change_attempts_' . $user->id;
        $attempts = Cache::get($throttleKey, 0);
        $maxAttempts = 3;
        $decayMinutes = 60;

        if ($attempts >= $maxAttempts) {
            Log::warning('Usuario bloqueado por rate limiting', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'attempts' => $attempts
            ]);
            return true;
        }

        // Incrementar contador si es un intento de cambio
        if ($request->has('password') && $request->filled('password')) {
            Cache::put($throttleKey, $attempts + 1, now()->addMinutes($decayMinutes));
        }

        return false;
    }

    /**
     * Registrar intento fallido
     *
     * @param $user
     * @param Request $request
     * @param string $reason
     */
    private function logFailedAttempt($user, Request $request, string $reason): void
    {
        Log::warning('Intento fallido de cambio de contraseña', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'reason' => $reason,
            'timestamp' => now()
        ]);
    }

    /**
     * Verificar si está en horario permitido para cambios
     *
     * @return bool
     */
    private function isAllowedTime(): bool
    {
        $currentHour = now()->hour;
        return $currentHour >= 6 && $currentHour < 22; // 6 AM a 10 PM
    }
}