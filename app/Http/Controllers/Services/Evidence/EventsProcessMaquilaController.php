<?php

namespace App\Http\Controllers\Services\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Evidence\StorageContainersTravel;
use App\Models\Evidence\StorageContainerTravelTrace;
use App\Models\Evidence\StorageEvidences;
use App\Models\Evidence\StorageMasterDetail;
use App\Models\Evidence\StorageMasterEvidence;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Traits\EvidencePhotoTrait;
use App\Traits\ResponseApi;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class EventsProcessMaquilaController extends BaseController
{
    use ResponseApi;
    use EvidencePhotoTrait;

    public function getStationWork()
    {
        $stations = SystemStatus::where('schemaid', 'EST')
            ->select(['statusid as station', 'name'])->get();
        return $this->getResponseSuccess('getStationWork', $stations);
    }


    public function saveContainerProcess(Request $request)
    {
        DB::beginTransaction();
        try {
            $center = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
            $warehouse = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('warehouseid');
            $lpn = StorageContainersTravel::controlAccessUser()->where('lpnid', '=', $request['lpnid'])->first();
            if (!empty($request['partnumberid'])) {
                $sku = $this->getPartnumber($request['partnumberid']);
            }
            if ($request['stationid'] == 'E1') {
                if ($request['lpnid'] == '' || $request['partnumberid'] == '') {
                    return $this->getResponseWithErrors('saveContainerProcess', 'Los valores de Lpn,Sku y cantidad son obligatorios.');
                }
                if (!empty($lpn)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El ID.Container ya se encuentra registrado, favor de validar.');
                }
                $lpn = $this->createContainerLpn($center, $warehouse, $request, $sku);
                $this->createKardexContainer($request, $lpn);
            }
            if ($request['stationid'] == 'E2') {
                if ($request['lpnid'] == '' || $request['quantity'] == '' || $request['partnumberid'] == '') {
                    return $this->getResponseWithErrors('saveContainerProcess', 'Los valores de ID.Container,Sku y cantidad son obligatorios');
                }
                if (empty($lpn)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'No existe ID.Container, valide con la estación 1');
                }
                $kardexIDContainer = StorageContainerTravelTrace::where('lpnid', '=', $request['lpnid'])
                    ->where('stationid', '=', 'E1')->first();
                if (empty($kardexIDContainer)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El ID.Container no cuenta con el evento de la estación 1');
                }
                $existUserAndContainer = $this->searchUserInTrace($request['lpnid']);
                if (!empty($existUserAndContainer)) {
                    return $this->getResponseWithErrors('', 'El usuario ya cuenta con registro de evento para este Contenedor, favor de validar.');
                }
//                Válida sku con el registro de la estacion 1
                $sku = $this->getPartnumber($request['partnumberid']);
                $existContaierSku = StorageContainersTravel::controlAccessUser()->where('lpnid', '=', $request['lpnid'])
                    ->where('partnumberid', '=', $sku->id)
                    ->select(['lpnid', 'partnumberid'])->first();
                if (empty($existContaierSku)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El número de parte no coincide con el Contenedor,favor de validar');
                }
                StorageContainersTravel::where('lpnid', '=', $request['lpnid'])->update(['quantity' => $request['quantity']]);
                $this->createKardexContainer($request, $lpn);
            }
            if ($request['stationid'] == 'E3') {
                if ($request['lpnid'] == '') {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El ID.Contaier es obligatorio.');
                }
                if (empty($lpn)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'No existe ID.Container, valide con la estación 1');
                }
                $kardexIDContainer = StorageContainerTravelTrace::where('lpnid', '=', $request['lpnid'])
                    ->where('stationid', '=', 'E2')->first();
                if (empty($kardexIDContainer)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El ID.Container no cuenta con el evento de la estación 2');
                }
                $existUserAndContainer = $this->searchUserInTrace($request['lpnid']);
                if (!empty($existUserAndContainer)) {
                    return $this->getResponseWithErrors('', 'El usuario ya cuenta con registro de evento para este Contenedor, favor de validar.');
                }
                $this->createKardexContainer($request, $lpn);
            }
            if ($request['stationid'] == 'E4') {
                if ($request['master'] == '' || $request['lpnid'] == '') {
                    return $this->getResponseWithErrors('saveContainerProcess', 'Los valores Master,ID. Container y Foto son obligatorios');
                }
                if (empty($lpn)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El número de ID. Container no existe.');
                }
                if ($lpn->statusid == 'D') {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El ID. Container ya se encuentra cerrado');
                }
                $kardexIDContainer = StorageContainerTravelTrace::where('lpnid', $request['lpnid'])
                    ->where('stationid', '=', 'E3')->first();
                if (empty($kardexIDContainer)) {
                    return $this->getResponseWithErrors('saveContainerProcess', 'El contenedor no cuenta con el evento de la estación 3');
                }
                $existUserAndContainer = $this->searchUserInTrace($request['lpnid']);
                if (!empty($existUserAndContainer)) {
                    return $this->getResponseWithErrors('', 'El usuario ya cuenta con registro de evento para este Contenedor, favor de validar.');
                }
                $masterExist = StorageMasterEvidence::controlAccessUser()->where('masterid', '=', $request['master'])->first();
                if (!empty($masterExist)) {
                    if ($masterExist->statusid != 'E') {
                        return $this->getResponseWithErrors('saveContainerProcess', 'El master ya se encuentra cerrado, favor de validar');
                    }
                }
                if (empty($masterExist)) {
                    $this->createMaster($request['master'], $center, $warehouse);
                }
                $lpn->value01 = $request['shipmentid'];
                $lpn->week = $request['week'];
                $lpn->customerid = $request['customerid'];
                $lpn->pallet = $request['pallet'];
                $lpn->location = $request['location'];
                $lpn->typeshipment = $request['typeshipment'];
                $lpn->save();
                $this->createKardexContainer($request, $lpn);
                $this->closeLpnContainer($request['lpnid']);
                $this->createDetailMaster($request, $center, $warehouse);
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('saveContainerProcess', []);
        } else {
            return $this->getResponseWithErrors('saveContainerProcess', 'Error al procesar la solicitud.');
        }
    }


    public function closeMasterContainer(Request $request)
    {
        if ($request['master'] == '') {
            return $this->getResponseWithErrors('closeMasterContainer', 'El master es un valor requerido.');
        }
        $masterExist = StorageMasterEvidence::controlAccessUser()->where('masterid', '=', $request['master'])->first();
        if (empty($masterExist)) {
            return $this->getResponseWithErrors('closeMasterContainer', 'El número de Master no existe, favor de validar');
        }
        if ($masterExist->statusid == 'D') {
            return $this->getResponseWithErrors('closeMasterContainer', 'El Master ya se encuentra cerrado');
        }
        StorageMasterEvidence::where('masterid', '=', $request['master'])
            ->update([
                'statusid' => 'D',
                'closeuser' => Auth::user()->username,
                'closedate' => Carbon::now(),
            ]);
        return $this->getResponseWithSuccess('closeMasterContainer', 'Etiqueta Master cerrada con exito.');
    }

    public function createKardexContainer(Request $request, $lpn)
    {
        StorageContainerTravelTrace::create([
            'centerid' => $lpn->centerid,
            'warehouseid' => $lpn->warehouseid,
            'lpnid' => $lpn->lpnid,
            'stationid' => $request['stationid'],
        ]);
    }

    public function saveFileEvidencieInDC(Request $request)
    {
        if ($request['file'] == '' || $request['lpnid'] == '') {
            return $this->getResponseWithErrors('saveFileEvidencieInDC', 'La foto y el lpn son valores obligatorios.');
        }
        $lpn = StorageContainersTravel::controlAccessUser()->where('lpnid', '=', $request['lpnid'])->first();
        if (empty($lpn)) {
            return $this->getResponseWithErrors('saveFileEvidencieInDC', 'El número de container no existe, favor de validar.');
        }
        $path = $this->saveFileToModelBase64($request['file'], 'StorageContainersTravel', $lpn, 'EvidenceProcess', 'DC', 'Table');
        StorageEvidences::createNewWvidence($lpn, $path, 'StorageContainersTravel ', $lpn->lpnid);
        return $this->getResponseWithSuccess('saveFileEvidencieInDC', 'Evidencia agregado con exito.');
    }


    /**
     * @param Request $request
     * @param $center
     * @param $warehouse
     * @return void
     */
    public function createDetailMaster(Request $request, $center, $warehouse): void
    {
        $lpnMasterExist = StorageMasterDetail::controlAccessUser()
            ->where('masterid', $request['master'])
            ->where('lpnid', $request['lpnid'])
            ->first();
        if (empty($lpnMasterExist)) {
            StorageMasterDetail::create([
                'centerid' => $center,
                'warehouseid' => $warehouse,
                'masterid' => $request['master'],
                'lpnid' => $request['lpnid']
            ]);
        }
    }

    /**
     * @param $master
     * @param $center
     * @param $warehouse
     * @return void
     */
    public function createMaster($master, $center, $warehouse): void
    {
        StorageMasterEvidence::create([
            'centerid' => $center,
            'warehouseid' => $warehouse,
            'masterid' => $master
        ]);
    }


    public function closeLpnContainer($lpnid): void
    {
        StorageContainersTravel::where('lpnid', '=', $lpnid)
            ->update([
                'statusid' => 'D',
            ]);
    }

    public function createContainerLpn($center, $warehouse, Request $request, InventoryPartnumber $sku)
    {
        $lpn = StorageContainersTravel::create(
            [
                'centerid' => $center,
                'warehouseid' => $warehouse,
                'lpnid' => $request['lpnid'],
                'partnumberid' => $sku->id,
                'quantity' => 0,
                'value01' => $request['shipmentid']
            ]
        );
        return $lpn;
    }


    public function getPartnumber($partnumberid)
    {
        $sku = InventoryPartnumber::controlAccessUser()->where('prtnum', $partnumberid)
            ->select(['id', 'prtnum'])->first();
        return $sku;
    }


    public function searchUserInTrace($lpnid)
    {
        return StorageContainerTravelTrace::where('scanuser', '=', Auth::user()->username)
            ->where('lpnid', '=', $lpnid)->first();
    }

    public function getDataContainerDC(Request $request)
    {
        if ($request['lpnid'] == '') {
            return $this->getResponseWithErrors('', 'El número de Container es obligatorio.');
        }
        $lpn = StorageContainersTravel::controlAccessUser()->where('lpnid', '=', $request['lpnid'])->first();
        if (empty($lpn)) {
            return $this->getResponseWithErrors('', 'El número de container no existe, favor de validar.');
        }
        return $this->getResponseSuccess('', [
            'lpnid' => $lpn->lpnid,
            'partnumber' => $lpn->sku->prtnum,
            'description' => $lpn->sku->lngdsc,
            'quantity' => $lpn->quantity
        ]);
    }


}
