<?php

namespace App\Traits\Api\General;

use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ErrorResponse
{

    protected function errorResponse($validator, $serviceName)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => $serviceName,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status' => 'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ], 422);
        throw new HttpResponseException($response);
    }



}
