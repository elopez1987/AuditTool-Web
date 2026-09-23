<?php

namespace App\ViewModels\Configuration;

use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\Services\Configuration\ServiceSystemWarehouse;
use App\Services\Configuration\SystemSchemaService;
use App\Services\Configuration\SystemStatusService;

readonly class SystemSchemaStatusViewModel
{
    public function __construct(
        private SystemStatusService $statusService,
        private SystemSchemaService $schemaService,
        private ServiceSystemCenterDistribution $centerDistribution,
        private ServiceSystemWarehouse $systemWarehouse,
    ) {}

    public function get(string $id): array
    {
        return [
            'systemschema' => $this->schemaService->findById($id),
            'centers' => $this->centerDistribution->getCenters(),
            'warehouses' => $this->systemWarehouse->getWarehouses(),
            'statusList' => $this->statusService->findBySchema($id),
        ];
    }
}
