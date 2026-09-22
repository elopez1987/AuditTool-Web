<?php

namespace App\Services\Configuration;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Repositories\SystemCenterDistributionRepositoryInterface;
use App\Repositories\SystemStatusRepositoryInterface;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ServiceSystemCenterDistribution
{
    use EvidencePhotoTrait;

    public function __construct(
        private readonly SystemCenterDistributionRepositoryInterface $repository,
        private readonly SystemStatusRepositoryInterface $statusRepository,
    ) {}

    public function findOrFail(string $centerid): SystemCenterDistribution
    {
        return $this->repository->findOrFail($centerid);
    }

    public function search(?string $name): Builder
    {
        return $this->repository->search($name);
    }

    public function getCenters(): Collection
    {
        return $this->repository->getCenters();
    }

    public function getAccessibleCenters(): Collection
    {
        return $this->repository->search(null)->get();
    }

    /** Distributions centers accessible */
    public function getCenterAll(): Collection
    {
        return $this->repository->searchAll();
    }

    public function getStatusForSchema(string $schemaid): Collection
    {
        return $this->statusRepository->findStatusForSchema($schemaid);
    }

    public function store(array $data): SystemCenterDistribution
    {
        DB::beginTransaction();
        try {
            $systemcenterdistribution = $this->repository->create($data);

            DB::commit();

            return $systemcenterdistribution;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al crear el centro de distribución', $e);

            throw $e;
        }
    }

    public function update(SystemCenterDistribution $systemcenterdistribution, array $data, Request $request): SystemCenterDistribution
    {
        DB::beginTransaction();
        try {
            $this->repository->update($systemcenterdistribution, $data);

            if ($request->hasFile('file')) {
                $this->updateLogo($systemcenterdistribution, $request);
            }

            DB::commit();

            return $systemcenterdistribution;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar el centro de distribución', $e, $systemcenterdistribution->centerid);

            throw $e;
        }
    }

    public function updateConfigurationFlag(SystemCenterDistribution $systemcenterdistribution, array $data): SystemCenterDistribution
    {
        DB::beginTransaction();
        try {
            $parameter = $data['parameter'] ?? [];

            $this->repository->update($systemcenterdistribution, [
                'configurationflag' => $parameter,
            ]);

            DB::commit();

            return $systemcenterdistribution;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar los parámetros del centro de distribución', $e, $systemcenterdistribution->centerid);

            throw $e;
        }
    }

    public function updateAppParameters(SystemCenterDistribution $systemcenterdistribution, array $data): SystemCenterDistribution
    {
        DB::beginTransaction();
        try {
            $parameter = $data['parameter'] ?? [];

            $this->repository->update($systemcenterdistribution, [
                'auxiliary05' => $parameter,
                'notificationsflags' => $parameter,
            ]);

            DB::commit();

            return $systemcenterdistribution;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar los parámetros de app del centro de distribución', $e, $systemcenterdistribution->centerid);

            throw $e;
        }
    }

    public function updateNotifications(SystemCenterDistribution $systemcenterdistribution, array $data): SystemCenterDistribution
    {
        DB::beginTransaction();
        try {
            $this->repository->update($systemcenterdistribution, [
                'notificationsflags' => $data['parameter'] ?? [],
            ]);

            DB::commit();

            return $systemcenterdistribution;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Error al actualizar las notificaciones del centro de distribución', $e, $systemcenterdistribution->centerid);

            throw $e;
        }
    }

    /**
     * Catalog of available folder definitions (FOLDERS parameter), shared
     * across all centers.
     */
    public function getFolderCatalog(): Collection
    {
        return SystemDetailParameter::where('parameterid', 'FOLDERS')
            ->select(['id', 'value01', 'value02'])
            ->get();
    }

    /**
     * Directories already created for the given center.
     */
    public function getDirectories(string $centerid): array
    {
        return Storage::directories('public/' . $centerid);
    }

    /**
     * Creates the selected folders for the center the filesystem.
     * Filesystem-only operation: no DB transaction is needed here.
     */
    public function createFolders(string $centerid, array $folders): void
    {
        if (empty($folders)) {
            return;
        }

        try {
            foreach ($folders as $folder) {
                Storage::makeDirectory("public/{$centerid}/{$folder}");
            }
        } catch (Throwable $e) {
            $this->logError('Error al crear directorios del centro de distribución', $e, $centerid);

            throw $e;
        }
    }

    /**
     * Replaces the center's logo, removing the previous file when present.
     *
     * NOTE: `auxiliary04` is stored relative to the default ("local") disk root
     * (e.g. "public/ImageProfile/archivo.jpg"), matching what addMediaToModel()
     * writes. It must NOT be prefixed with "storage/" when checking/deleting it,
     * otherwise Storage::exists()/delete() silently no-op and old logos are
     * never removed.
     */
    private function updateLogo(SystemCenterDistribution $systemcenterdistribution, Request $request): void
    {
        if (! empty($systemcenterdistribution->auxiliary04) && Storage::exists($systemcenterdistribution->auxiliary04)) {
            Storage::delete($systemcenterdistribution->auxiliary04);
        }

        $path = $this->addMediaToModel($request, 'ImageProfile');
        $systemcenterdistribution->auxiliary04 = $path;
        $systemcenterdistribution->save();
    }

    private function logError(string $message, Throwable $e, ?string $centerid = null): void
    {
        Log::error($message, [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString(),
            'centerid' => $centerid,
        ]);
    }
}
