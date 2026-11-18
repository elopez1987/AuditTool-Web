<?php

declare(strict_types=1);

namespace App\ViewModels\Security;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

final class LocalLoginViewModel
{
    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public function forLogin(array $context): array
    {
        $user = $context['user'];
        $center = $context['center'];
        $device = $context['device'];
        $data = [
            'user' => [
                'name' => $user->name,
                'centerId' => $center->centerid,
                'centerName' => $center->name,
            ],
            'permissions' => $user->getAllPermissions()->pluck('name')->unique()->sort()->values()->all(),
            'parameters' => [
                'app' => $this->flags($center->mobileflags),
                'user' => $this->flags($user->configurationflag),
                'warehouse' => (object) $context['warehouses']
                    ->mapWithKeys(fn (SystemWarehouse $warehouse): array => [
                        $warehouse->warehouseid => $this->flags($warehouse->configurationflag),
                    ])->all(),
            ],
            'device' => [
                'registered' => $device !== null,
                'is_new' => $device?->wasRecentlyCreated ?? false,
                'warning' => $device === null ? 'No se proporcionó número de serie del dispositivo' : null,
            ],
        ];

        if (isset($context['auth'])) {
            $auth = $context['auth'];
            $token = (new Parser(new JoseEncoder))->parse($auth['access_token']);
            $expires = Carbon::instance($token->claims()->get('exp'))->utc();
            $data['auth'] = [
                'accessToken' => $auth['access_token'],
                'tokenType' => $auth['token_type'],
                'expiresIn' => $auth['expires_in'],
                'expiresAt' => $expires->format('Y-m-d H:i:s'),
                'refreshToken' => $auth['refresh_token'],
            ];
        }

        return $data;
    }

    /**
     * @return list<string>
     */
    private function flags(mixed $value): array
    {
        return is_array($value)
            ? array_values(array_filter($value, static fn (mixed $flag): bool => is_string($flag)))
            : [];
    }
}
