<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Repositories\Security\LocalAuthenticationAuditRepository;
use Psr\Log\LoggerInterface;
use Throwable;

final class LocalAuthenticationAuditService
{
    public function __construct(
        private readonly LocalAuthenticationAuditRepository $records,
        private readonly LoggerInterface $logger,
    ) {}

    /** @param array{username: string, user_id: ?int, ip_address: string, successful: bool, reason: string, request_method: string, request_path: string, session_id: ?string} $context */
    public function record(array $context): void
    {
        try {
            $this->records->record($context);
        } catch (Throwable $exception) {
            // Do not log exception messages: query exceptions may contain sensitive bindings.
            $this->logger->error('Local authentication audit persistence failed.', [
                'exception_type' => $exception::class,
                'reason' => $context['reason'],
            ]);
        }
    }
}
