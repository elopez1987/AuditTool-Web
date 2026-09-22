<?php

namespace App\Services\Configuration;

use App\Models\Configuration\Schemas\SystemSchema;
use App\Repositories\Configuration\SystemSchemaRepository;
use Illuminate\Database\Eloquent\Builder;

readonly class SystemSchemaService
{
    public function __construct(
        private SystemSchemaRepository $repository,
    ) {}

    public function findById(string $schemaId)
    {
        return $this->repository->findById($schemaId);
    }

    public function findAll(array $data): Builder
    {
        return $this->repository->findAll($data);
    }

    public function newSchema(array $data): SystemSchema
    {
        return $this->repository->storeSchema($data);
    }
}
