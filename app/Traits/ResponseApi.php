<?php


namespace App\Traits;


use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

trait ResponseApi
{

    public function getCenterForUserForApi()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
    }

    public function getWarehouseForUserForApi()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)->value('warehouseid');
    }

    public function showAll($service,$collection): JsonResponse
    {
        return $this->getResponseSuccess($service, $collection);
    }

    public function showOne($service,$model,): JsonResponse
    {
        return $this->getResponseSuccess($service, $model);
    }

    public function getResponse($service, $code, $message, $data, $status): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => $code,
            'Message' => $message,
            'Data' => $data,
        ], $status);
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

    public function getResponseSuccessWithMessage($service, $data, $message, $code): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => $code,
            'Message' => $message,
            'Data' => $data,
        ], '200');
    }

    public function getResponseWithErrors($service, $message): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status'=>'Error',
            'Message' => $message,
            'Data' => [],
        ]);
    }

    public function getResponseLocationCount($service, $message): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '2',
            'Status' => 'Warning',
            'Message' => $message,
            'Data' => [],
        ]);
    }

    public function getResponseRegisterYard($service, $message,$code,$data): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => $code,
            'Message' => $message,
            'Data' => $data,
        ], '200');
    }

    public function getResponseWithSuccess($service, $message): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Status' => 'Success',
            'Message' => $message,
            'Data' => [],
        ], '200');
    }

    public function getResponseWithErrorsData($service, $message, $data): JsonResponse
    {
        return response()->json([
            'ServiceName' => $service,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Message' => $message,
            'Data' => $data,
        ]);
    }



}
