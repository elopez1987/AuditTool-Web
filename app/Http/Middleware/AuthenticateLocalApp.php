<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\LocalAuthException;
use App\Services\Security\LocalAccountService;
use App\Services\Security\LocalSessionService;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateLocalApp
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly LocalAccountService $accounts,
        private readonly Repository $config,
        private readonly LocalSessionService $sessions,
        private readonly RateLimiter $limiter,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->bearerToken()) {
            throw new LocalAuthException('Se requiere un access token válido.');
        }

        $previous = $this->auth->getDefaultDriver();
        $guard = $this->auth->guard('local-app');
        $guard->setRequest($request)->forgetUser();
        $this->auth->shouldUse('local-app');

        try {
            $user = $guard->user();
            if ($user === null || (string) $guard->client()?->id !== (string) $this->config->get('local_auth.client_id')) {
                throw new LocalAuthException('Se requiere un access token válido.');
            }
            try {
                $user = $this->accounts->fromId($user->id);
            } catch (LocalAuthException $exception) {
                $this->sessions->revokeUser((int) $user->id);
                throw $exception;
            }
            $metadata = $this->sessions->validateAccess($user, $guard->user()->token()->id);
            $request->attributes->set('local_auth_user_id', (int) $user->id);
            $request->attributes->set('local_auth_username', (string) $user->username);
            $request->attributes->set('local_auth_family_id', $metadata->session_family_id);
            if ($request->route()?->getName() === 'v1.auth.me') {
                $key = 'local-auth:profile:user:'.$user->id;
                if ($this->limiter->tooManyAttempts($key, (int) $this->config->get('local_auth.limits.profile_user', 120))) {
                    throw new LocalAuthException('Demasiadas solicitudes. Inténtalo más tarde.', 429,
                        'rate_limited', ['Retry-After' => (string) $this->limiter->availableIn($key)]);
                }
                $this->limiter->hit($key, 60);
            }

            return $next($request);
        } finally {
            $guard->forgetUser();
            $this->auth->shouldUse($previous);
        }
    }
}
