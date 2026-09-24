<?php

namespace App\ViewModels\Yard;

use App\Services\Configuration\ServiceSystemCenterDistribution;

readonly class GpsDeviceViewModel
{
    public function __construct(
        private ServiceSystemCenterDistribution $centerDistribution,
    ) {}

    public function index()
    {
        return [
            'centers' => $this->centerDistribution->getCenters(),
        ];
    }
}
