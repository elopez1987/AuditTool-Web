<?php

namespace App\Services\Configuration;

use App\Models\Configuration\Parameters\SystemParameter;
use App\Repositories\Configuration\SystemParameterRepository;
use Illuminate\Database\Eloquent\Builder;

readonly class SystemParameterService
{
    public function __construct(
        private SystemParameterRepository $parameterRepository,
    ) {}

    public function all(array $data): Builder
    {
        return $this->parameterRepository->all($data);
    }

    public function store(array $data): SystemParameter
    {
        return $this->parameterRepository->store($data);
    }

    public function update(array $data): SystemParameter
    {
        return $this->parameterRepository->update($data);
    }

    public function destroy(SystemParameter $systemParameter)
    {
        $this->parameterRepository->destroy($systemParameter);
    }
}
