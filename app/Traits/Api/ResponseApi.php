<?php

namespace App\Traits\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

trait ResponseApi
{
    public function showAll($service, $collection): JsonResponse
    {
        return $this->getResponseSuccess($service, $collection);
    }

    public function showOne($service, $model): JsonResponse
    {
        return $this->getResponseSuccess($service, $model);
    }

    public function getResponseSuccess($service, $data): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => 'Proceso efectuado correctamente',
            'Data' => $data,
        ], '200');
    }
}
