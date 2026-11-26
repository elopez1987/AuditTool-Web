<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Receipt\VerificationWebServiceAuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class AuditVerificationWebService
{
    public function __construct(private readonly VerificationWebServiceAuditService $audit) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $content = $response->getContent();
        $context = [
            'endpoint' => basename($request->path()),
            'status' => $response->getStatusCode(),
            'path' => $request->path(),
        ];

        Log::debug('Verification web service audit termination started.', $context);

        $persisted = $this->audit->record(
            payload: $request->all(),
            response: is_string($content) ? (json_decode($content, true) ?? $content) : null,
            status: $context['status'],
            endpoint: $context['endpoint'],
            username: $request->attributes->get('local_auth_username') ?? $request->user()?->username,
            method: $request->method(),
            path: $context['path'],
            ipAddress: $request->ip(),
        );

        Log::debug('Verification web service audit persistence completed.', [
            ...$context,
            'persisted' => $persisted,
        ]);
    }
}
