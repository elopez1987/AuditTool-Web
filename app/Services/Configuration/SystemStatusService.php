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
}
