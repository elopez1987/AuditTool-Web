<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\LocalAuthException;
use App\Services\Security\LocalAccountService;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateLocalApp
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly LocalAccountService $accounts,
        private readonly Repository $config,
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
            $this->accounts->fromId($user->id);

            return $next($request);
        } finally {
            $guard->forgetUser();
            $this->auth->shouldUse($previous);
        }
    }
}
