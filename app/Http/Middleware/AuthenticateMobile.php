<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Security\MobileAuthConfiguration;
use Auth0\SDK\Exception\ConfigurationException;
use Auth0\SDK\Exception\InvalidTokenException;
use Auth0\SDK\Exception\NetworkException;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateMobile
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly MobileAuthConfiguration $configuration,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->configuration->isConfigured()) {
            return $this->error('MOBILE_AUTH_UNAVAILABLE', 'Autenticación móvil no disponible.', 503);
        }

        if ($request->bearerToken() === null || trim($request->bearerToken()) === '') {
            return $this->error('UNAUTHENTICATED', 'Se requiere un access token válido.', 401);
        }

        $previousGuard = $this->auth->getDefaultDriver();
        $guard = $this->auth->guard('auth0-mobile');
        $guard->forgetUser();
        $this->auth->shouldUse('auth0-mobile');

        try {
            try {
                if ($guard->user() === null) {
                    return $this->error('UNAUTHENTICATED', 'Se requiere un access token válido.', 401);
                }
            } catch (AuthenticationException|InvalidTokenException) {
                return $this->error('UNAUTHENTICATED', 'Se requiere un access token válido.', 401);
            } catch (AuthorizationException) {
                return $this->error('MOBILE_ACCESS_DENIED', 'No tienes acceso a la aplicación móvil.', 403);
            } catch (ConfigurationException|NetworkException) {
                return $this->error('MOBILE_AUTH_UNAVAILABLE', 'Autenticación móvil no disponible.', 503);
            }

            $response = $next($request);
            $response->headers->set('Cache-Control', 'no-store, private');
            $response->headers->set('Pragma', 'no-cache');

            return $response;
        } finally {
            $guard->forgetUser();
            $this->auth->shouldUse($previousGuard);
        }
    }

    private function error(string $code, string $message, int $status): JsonResponse
    {
        $response = new JsonResponse([
            'error' => $code,
            'message' => $message,
        ], $status, [
            'Cache-Control' => 'no-store, private',
            'Pragma' => 'no-cache',
        ]);

        if ($status === 401) {
            $response->headers->set('WWW-Authenticate', 'Bearer');
        }

        return $response;
    }
}
