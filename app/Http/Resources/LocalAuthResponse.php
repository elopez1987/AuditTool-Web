<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

final class LocalAuthResponse extends JsonResponse
{
    /**
     * @param  array<string, mixed>|null  $data
     * @param  array<string, mixed>  $headers
     */
    public function __construct(int $status, string $message, ?array $data, string $service = 'login-user', array $headers = [])
    {
        parent::__construct([
            'success' => $status >= 200 && $status < 300,
            'code' => $status,
            'message' => $message,
            'meta' => [
                'version' => '1.0.0',
                'service' => $service,
                'processDate' => Carbon::now('UTC')->toISOString(),
            ],
            'data' => $data,
        ], $status, $headers + [
            'Cache-Control' => 'no-store, private',
            'Pragma' => 'no-cache',
        ]);
    }
}
