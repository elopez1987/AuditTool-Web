<?php

namespace App\Services\Configuration;

use App\Models\Configuration\ActivityLog\ActivityLog;
use App\Repositories\Configuration\ActivityLogRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

readonly class ActivityLogService
{
    public function __construct(
        private ActivityLogRepository $logRepository,
    ) {}

    public function findById(int $Id): ActivityLog
    {
        return $this->logRepository->findById($Id);
    }

    public function findByDescription(string $descripton): Collection
    {
        return $this->logRepository->findByDescription($descripton);
    }

    public function all(array $data): Builder
    {
        return $this->logRepository->all($data);
    }
}
