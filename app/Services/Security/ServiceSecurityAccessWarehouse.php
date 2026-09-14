<?php

namespace App\Services\Security;

use App\Repositories\SecurityAccessWarehouseRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class ServiceSecurityAccessWarehouse
{
    public function __construct(private SecurityAccessWarehouseRepositoryInterface $repository) {}

    public function getAccessesForUser(string $username): Collection
    {
        return $this->repository->findForUser($username);
    }

    /**
     * Fija la bodega/centro por defecto del usuario: limpia el flag en todos
     * sus accesos y lo activa unicamente en el seleccionado.
     */
    public function setDefaultWarehouse(string $username, int $accessId): void
    {
        DB::beginTransaction();
        try {
            $this->repository->resetDefaultLevel($username);
            $this->repository->setDefaultLevel($accessId, $username);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al fijar la bodega por defecto del usuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
                'username' => $username,
                'access_id' => $accessId,
            ]);

            throw $e;
        }
    }
}
