<?php

namespace App\Services\Configuration;

use App\Repositories\Configuration\SystemStatusRepository;
use Illuminate\Support\Collection;

readonly class SystemStatusService
{
    public function __construct(
        private SystemStatusRepository $repository,
    ) {}

    public function findBySchema(string $schemaId): Collection
    {
        return $this->repository->findStatusForSchema($schemaId);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update(array $data)
    {
        return $this->repository->update($data);
    }

    public function destroy(string $statusId)
    {
        $this->repository->destroy($statusId);
    }
}
