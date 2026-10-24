<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\LocalAuthResponse;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocalAuthContext
{
    public function __construct(private readonly Repository $config) {}

    public function handle(Request $request, Closure $next): Response
    {
        $service = match ($request->route()?->getName()) {
            'v1.auth.refresh' => 'refresh-user',
            'v1.auth.me' => 'current-user',
            default => 'login-user',
        };
        $expected = $this->config->get('local_auth.token_app');
        $provided = $request->header('X-App-Token');

        if (! is_string($expected) || strlen($expected) < 32) {
            return new LocalAuthResponse(503, 'El servicio de autenticación local no está configurado.', null, $service);
        }
        if (! is_string($provided) || $provided === '' || strlen($provided) > 512
            || ! hash_equals(hash('sha256', $expected), hash('sha256', $provided))) {
            return new LocalAuthResponse(401, 'Token de aplicación inválido.', null, $service);
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}
