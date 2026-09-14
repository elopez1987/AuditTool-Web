<?php

namespace App\Services\Security;

use App\Exceptions\CatalogEntityNotFoundException;
use App\Repositories\Eloquent\SecurityAccessWarehouseRepository;
use App\Repositories\Security\AccessOriginRepository;
use Illuminate\Support\Collection;

readonly class AccessOriginService
{
    public function __construct(
        private AccessOriginRepository $repository,
        private SecurityAccessWarehouseRepository $warehouseRepository,
    ) {}

    public function getOrigingForUser(string $username): Collection
    {
        return $this->repository->getAllForUser($username);
    }

    public function store(array $data): void
    {
        $centerId = $this->warehouseRepository->getCenterIdByUsername($data['username']);

        if ($centerId === null) {
            throw CatalogEntityNotFoundException::centerAccess($data['username']);
        }

        $this->repository->save($data, $centerId);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
