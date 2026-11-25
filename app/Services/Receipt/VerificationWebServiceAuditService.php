<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use App\Repositories\Receipt\VerificationWebServiceAuditRepository;
use Psr\Log\LoggerInterface;
use Throwable;

final class VerificationWebServiceAuditService
{
    public function __construct(
        private readonly VerificationWebServiceAuditRepository $records,
        private readonly LoggerInterface $logger,
    ) {}

    /** @param array<string, mixed> $payload */
    public function record(
        array $payload,
        mixed $response,
        int $status,
        string $endpoint,
        ?string $username,
        string $method,
        string $path,
        ?string $ipAddress,
    ): void {
        try {
            $asnId = $this->reference($payload['AsnId'] ?? null);
            $voucherId = $this->reference($payload['VoucherId'] ?? null);
            $message = is_array($response) ? ($response['message'] ?? null) : null;

            $this->records->record([
                ...$this->records->receiptContext($asnId, $voucherId),
                'reference' => mb_substr($voucherId ?? $asnId ?? '', 0, 40),
                'servicename' => mb_substr($endpoint, 0, 60),
                'processdate' => now()->format('Y-m-d H:i:s'),
                'code' => (string) $status,
                'type' => $status < 400 ? 'SUCCESS' : 'ERROR',
                'message' => mb_substr(is_string($message) ? $message : 'HTTP '.$status, 0, 300),
                'messagejson' => $this->redact($payload),
                'messagejsonresponse' => $this->redact($response),
                'value01' => $username !== null ? mb_substr($username, 0, 60) : null,
                'value02' => mb_substr($method, 0, 60),
                'value03' => $ipAddress !== null ? mb_substr($ipAddress, 0, 60) : null,
                'value04' => mb_substr($path, 0, 100),
            ]);
        } catch (Throwable $exception) {
            $this->logger->error('Verification web service audit persistence failed.', [
                'exception_type' => $exception::class,
                'endpoint' => $endpoint,
                'status' => $status,
            ]);
        }
    }

    private function reference(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }

    private function redact(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            $normalizedKey = strtolower(str_replace(['_', '-'], '', (string) $key));
            $value[$key] = in_array($normalizedKey, [
                'password', 'passwordconfirmation', 'authorization', 'accesstoken',
                'refreshtoken', 'clientsecret', 'token', 'tokenapp', 'apikey',
            ], true) ? '[REDACTED]' : $this->redact($item);
        }

        return $value;
    }
}
