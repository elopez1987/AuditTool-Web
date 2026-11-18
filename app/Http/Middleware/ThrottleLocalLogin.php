<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\LocalAuthException;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleLocalLogin
{
    public function __construct(private readonly RateLimiter $limiter, private readonly Repository $config) {}

    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->input('username');
        if (! is_string($username) || mb_strlen($username) > 20 || trim($username) === '') {
            return $next($request);
        }

        $account = hash('sha256', mb_strtolower(trim($username)));
        $pairKey = 'local-auth:login:pair:'.$account.':'.$request->ip();
        $accountKey = 'local-auth:login:account:'.$account;
        foreach ([
            $pairKey => (int) $this->config->get('local_auth.limits.login_failures_pair', 5),
            $accountKey => (int) $this->config->get('local_auth.limits.login_failures_account', 20),
        ] as $key => $maximum) {
            if ($this->limiter->tooManyAttempts($key, $maximum)) {
                throw new LocalAuthException('Demasiadas solicitudes. Inténtalo más tarde.', 429,
                    'rate_limited', ['Retry-After' => (string) $this->limiter->availableIn($key)]);
            }
        }

        try {
            $response = $next($request);
        } catch (LocalAuthException|OAuthServerException $exception) {
            if ($exception instanceof OAuthServerException || $exception->status === 401) {
                $this->limiter->hit($pairKey, 60);
                $this->limiter->hit($accountKey, 900);
            }
            throw $exception;
        }

        if ($response->getStatusCode() === 401) {
            $this->limiter->hit($pairKey, 60);
            $this->limiter->hit($accountKey, 900);
        } elseif ($response->isSuccessful()) {
            $this->limiter->clear($pairKey);
        }

        return $response;
    }
}
