<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateMoveBoxRequest;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Models\Yard\StorageZone\StorageZone;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class MoveBoxController extends BaseController
{
    public function index()
    {
        $this->authorize('movebox');
        $zones = $this->getZonesForMovementInternal();
        $locations = StorageLocation::activeLocationForZone();

        return view(
            'Yards.MoveBoxes.index',
            compact('zones', 'locations'),
        );
    }

    public function storeMoveBox(CreateMoveBoxRequest $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de folio no existe!!',
                ], 422);
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $zone = $this->getZone($request['zoneid']);
            if (! empty($parameter) && in_array('SWH-39', $parameter)) {
                if ($receiptunit->deliverdate === null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La unidad no cuenta con llegada a mesa de control,favor de realizar el evento.',
                    ], 422);
                }
            }
            if ($receiptunit->razonstatusid === 2) {
                if (! empty($parameter) && in_array('SWH-36', $parameter)) {
                    if ($zone->zoneid === 'CO' && $receiptunit->deliverdate === null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La unidad no cuenta con llegada a mesa de control,favor de realizar el evento.',
                        ], 422);
                    }
                }
                if ($zone->zoneid === 'CO') {
                    ReceiptAsn::updateStatusForZone($receiptunit->id);
                }
            }
            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            [$status, $razonstatusid] = $this->getStatusAndRazonStatus($receiptunit, $request);
            self::updateUnitForMoveOfLocations($request, $receiptunit, $status);
            StorageLocation::updateLocation($request['locationid'], 'D', $receiptunit->boxnumber);
            StorageUnitTrace::createTraceUnit($receiptunit, $razonstatusid);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Unidad reubicada con exito!!',
            ], 200);
        } catch (Exception $e) {
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $error,
            ], 500);
        }
    }

    private function updateUnitForMoveOfLocations($request, $receiptunit, $status)
    {
        $receiptunit->zoneid = $request['zoneid'];
        $receiptunit->statusid = $status;
        $receiptunit->locationid = $request['locationid'];
        //		if ($request['unitstatusid'] !== '') {
        //			$receiptunit->unitstatusid = $request['unitstatusid'];
        //		}
        $receiptunit->save();
    }

    private function getStatusAndRazonStatus(ReceiptUnit $receiptunit, Request $request): array
    {
        if ($receiptunit->statusid !== 'OU') {
            $status = $this->getZoneId($request, $receiptunit);
        } else {
            $status = $receiptunit->statusid;
        }
        if ($status === 'EN') {
            $receiptunit->ensnare = now();
            $razonstatusid = '13';
        } elseif ($status === 'IN') {
            $receiptunit->unravel = now();
            $razonstatusid = '16';
        } else {
            $razonstatusid = '26';
        }

        return [$status, $razonstatusid];
    }

    private function getZone($zoneid)
    {
        return StorageZone::controlAccessUser()->where('id', $zoneid)->select(['id', 'zoneid'])->first();
    }

    private function getZoneId($request, $receiptunit)
    {
        $zoneid = StorageZone::whereId($request['zoneid'])->value('zoneid');
        if ($zoneid === 'CO') {
            if ($receiptunit->statusid === 'EP') {
                $status = $receiptunit->statusid;
            } else {
                $status = 'EN';
            }
        } elseif ($zoneid === 'PA') {
            $status = 'IN';
        }

        return $status;
    }
}
