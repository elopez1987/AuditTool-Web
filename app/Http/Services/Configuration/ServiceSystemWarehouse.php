<?php

namespace App\Http\Services\Configuration;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Repositories\SystemStatusRepositoryInterface;
use App\Repositories\SystemWarehouseRepositoryInterface;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ServiceSystemWarehouse
{
    use EvidencePhotoTrait;

    public function __construct(
        private readonly SystemWarehouseRepositoryInterface $repository,
        private readonly SystemStatusRepositoryInterface $statusRepository,
    ) {}

    public function findOrFail(string $warehouseid): SystemWarehouse
    {
        return $this->repository->findOrFail($warehouseid);
    }

    public function search(?string $name): Builder
    {
        return $this->repository->search($name);
    }

    /**
     * Statuses for the given schema (STS/SWH/MWH), reusing the shared
     * SystemStatus repository already introduced for CenterDistribution.
     */
    public function getStatusForSchema(string $schemaid): Collection
    {
        return $this->statusRepository->findStatusForSchema($schemaid);
    }

    public function store(array $data): SystemWarehouse
    {
        DB::beginTransaction();
        try {
            $systemwarehouse = $this->repository->create($data);

            DB::commit();

            return $systemwarehouse;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al crear la bodega', $e);

            throw $e;
        }
    }

    public function update(SystemWarehouse $systemwarehouse, array $data, Request $request): SystemWarehouse
    {
        DB::beginTransaction();
        try {
            $this->repository->update($systemwarehouse, $data);

            if ($request->hasFile('file')) {
                $this->updateLogo($systemwarehouse, $request);
            }

            DB::commit();

            return $systemwarehouse;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar la bodega', $e, $systemwarehouse->warehouseid);

            throw $e;
        }
    }

    /**
     * Updates the SWH schema "configurationflag" checkboxes.
     */
    public function updateConfigurationFlag(SystemWarehouse $systemwarehouse, array $data): SystemWarehouse
    {
        DB::beginTransaction();
        try {
            $this->repository->update($systemwarehouse, [
                'configurationflag' => $data['parameter'] ?? [],
            ]);

            DB::commit();

            return $systemwarehouse;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar los parámetros de la bodega', $e, $systemwarehouse->warehouseid);

            throw $e;
        }
    }

    /**
     * Updates the MWH schema notification checkboxes.
     */
    public function updateNotifications(SystemWarehouse $systemwarehouse, array $data): SystemWarehouse
    {
        DB::beginTransaction();
        try {
            $this->repository->update($systemwarehouse, [
                'notificationsflags' => $data['parameter'] ?? [],
            ]);

            DB::commit();

            return $systemwarehouse;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar las notificaciones de la bodega', $e, $systemwarehouse->warehouseid);

            throw $e;
        }
    }

    /**
     * Replaces the warehouse's logo, removing the previous file when present.
     *
     * NOTE: `auxiliary09` is stored relative to the default ("local") disk root
     * (e.g. "public/ImageProfile/archivo.jpg"), matching what addMediaToModel()
     * writes. It must NOT be prefixed with "storage/" when checking/deleting it,
     * otherwise Storage::exists()/delete() silently no-op and old logos are
     * never removed (same bug already fixed for SystemCenterDistribution).
     */
    private function updateLogo(SystemWarehouse $systemwarehouse, Request $request): void
    {
        if (! empty($systemwarehouse->auxiliary09) && Storage::exists($systemwarehouse->auxiliary09)) {
            Storage::delete($systemwarehouse->auxiliary09);
        }

        $path = $this->addMediaToModel($request, 'ImageProfile');
        $systemwarehouse->auxiliary09 = $path;
        $systemwarehouse->save();
    }

    private function logError(string $message, Throwable $e, ?string $warehouseid = null): void
    {
        Log::error($message, [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString(),
            'warehouseid' => $warehouseid,
        ]);
    }
}
