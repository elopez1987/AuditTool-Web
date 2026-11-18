<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\LocalAuthException;
use App\Services\Security\LocalAuthenticationAuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final class AuditLocalAuthentication
{
    public function __construct(private readonly LocalAuthenticationAuditService $audit) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $status = match (true) {
                $exception instanceof LocalAuthException => $exception->status,
                $exception instanceof OAuthServerException => 401,
                $exception instanceof ValidationException => 422,
                $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
                default => 503,
            };
            $this->record($request, $status, $exception instanceof LocalAuthException ? $exception->reason : 'request_failed');
            throw $exception;
        }

        if ($request->is('api/v1/auth/*') || $response->getStatusCode() >= 400) {
            $this->record($request, $response->getStatusCode(), $response->isSuccessful() ? 'success' : 'request_denied');
        }

        return $response;
    }

    private function record(Request $request, int $status, string $reason): void
    {
        $username = $request->attributes->get('local_auth_username', $request->input('username'));
        $this->audit->record([
            'username' => is_string($username) ? mb_substr($username, 0, 100) : 'unknown',
            'user_id' => $request->attributes->get('local_auth_user_id'),
            'ip_address' => $request->ip() ?? 'unknown',
            'successful' => $status >= 200 && $status < 300,
            'reason' => $request->attributes->get('local_auth_failure_reason', $reason).':'.$status,
            'request_method' => $request->method(),
            'request_path' => mb_substr($request->path(), 0, 255),
            'session_id' => $request->attributes->get('local_auth_family_id'),
        ]);
    }
}
