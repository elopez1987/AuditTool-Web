<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class MobileUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource['user'];

        return [
            'id' => $user->id,
            'uuiduser' => $user->uuiduser,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->sort()->values()->all(),
            'permissions' => $user->getAllPermissions()->pluck('name')->unique()->sort()->values()->all(),
            'warehouse_access' => $this->resource['warehouse_access']
                ->map(static fn (SecurityAccessWarehouse $access): array => [
                    'centerid' => $access->centerid,
                    'warehouseid' => $access->warehouseid,
                ])->values()->all(),
        ];
    }
}
