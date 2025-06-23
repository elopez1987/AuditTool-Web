<?php

namespace App\Http\Controllers\Services\Evidence;

use App\Http\Controllers\Controller;
use App\Models\Evidence\EvidenceContainer;
use App\Models\Evidence\StorageEvidenceProcess;
use App\Models\Evidence\StorageEvidenceStock;
use App\Traits\Counting\QueryEvidenceContainer;
use App\Traits\EvidencePhotoTrait;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class EvidenceProcessController extends Controller
{
    use ResponseApi;
    use QueryEvidenceContainer;
    use EvidencePhotoTrait;

    public function getDataShipmentOrReceipt(Request $request)
    {
        if ($request['shipmentid'] == null) {
            return $this->getResponseWithErrors('getDataShipmentOrReceipt', 'El número de embarque es un valor obligatorio.');
        }
        $shipment = StorageEvidenceProcess::controlAccessUser()
            ->leftJoin('storagelocations', 'storageevidenceoperations.locationid', 'storagelocations.id')
            ->where('shipmentid', $request['shipmentid'])
            ->select(['storageevidenceoperations.shipmentid', 'storagelocations.name as location',
                'storageevidenceoperations.statusid'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataShipmentOrReceipt', 'No existe el embarque buscado.');
        }
        if ($shipment->statusid != 'E') {
            return $this->getResponseWithErrors('getDataShipmentOrReceipt', 'El embarque se encuentra finalizado.');
        }
        return $this->getResponseSuccess('getDataShipmentOrReceipt', $shipment);
    }

    public function savefiletoshipment(Request $request)
    {
        DB::beginTransaction();
        try {
            $shipment = $this->getShipment($request['shipmentid']);
            if ($shipment->statusid != 'E') {
                return $this->getResponseWithErrors('savefiletoshipment',
                    'El número de embarque ya se encuentra cerrado.');
            }
            $partnumberid = $this->getSku($request['partnumberid']);
            if (empty($partnumberid)) {
                return $this->getResponseWithErrors('savefiletoshipment',
                    'El número de parte no existe, favor de validar.');
            }
            $skuRequest = $this->validateRequestPartnumber($shipment, $partnumberid);
            if (empty($skuRequest)) {
                return $this->getResponseWithErrors('savefiletoshipment', 'El número de parte no fue solicitado en Cargo.');
            }
            $totalCheck = $this->getTotalCheckPartNumber($shipment, $partnumberid);
            if ($totalCheck[0]->verificado + $request['quantity'] > $skuRequest->quantity) {
                return $this->getResponseWithErrors('savefiletoshipment', 'La cantidad no puede ser mayor a la solicitada.');
            }
            $container = $this->saveContainerToShipment($shipment, $request, $partnumberid);
            if (!empty($request['file'])) {
                $path = $this->saveFile($request, $shipment);
                $container->filecheck = $path;
                $container->save();
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('savefiletoshipment', []);
        } else {
            return $this->getResponseWithErrors('savefiletoshipment',
                'Error al procesar la solicitud,favor de intentarlo nuevamente.');
        }
    }


//    Mapa de carga
    public function getDataShipmentEvidence(Request $request)
    {
        if ($request['shipmentid'] == null) {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'El número de embarque es obligatorio.');
        }
        $shipment = StorageEvidenceProcess::controlAccessUser()->where('shipmentid', '=', $request['shipmentid'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'No existen datos del embarque.');
        }
        if ($shipment->statusid != 'E') {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'El embarque se encuentra termiando.');
        }
        $data = $this->getShipmentContainer($shipment);
        return $this->getResponseSuccess('savefiletoshipment', $data);
    }


    public function saveLpnContainerInShipment(Request $request)
    {
        if ($request['shipmentid'] == null || $request['lpnid'] == null) {
            return $this->getResponseWithErrors('saveContainerInShipmentLoad',
                'El número de embarque,Lpn y Foto son valores obligatorios.');
        }
        $shipment = StorageEvidenceProcess::controlAccessUser()
            ->where('shipmentid', '=', $request['shipmentid'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'No existen datos del embarque.');
        }
        if ($shipment->statusid != 'E') {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'El embarque se encuentra termiando.');
        }
        $lpnContainer = EvidenceContainer::where('shipmentid', $shipment->recordid)
            ->where('lpnid', '=', $request['lpnid'])->first();
        if (empty($lpnContainer)) {
            return $this->getResponseWithErrors('getDataShipmentEvidence', 'El lpn no pertenece al embarque.');
        }
        $path = $this->saveFile($request, $shipment);
        EvidenceContainer::where('shipmentid', '=', $shipment->recordid)
            ->where('lpnid', '=', $request['lpnid'])
            ->update([
                'statusid' => 'SC',
                'scanuserload' => \Auth::user()->username,
                'adddateload' => Carbon::now(),
                'fileload' => $path,
            ]);
        $totalScan = EvidenceContainer::where('shipmentid', $shipment->recordid)
            ->where('statusid', '=', 'SC')
            ->distinct('lpnid')
            ->count();
        return $this->getResponseSuccess('savefiletoshipment', ['totalScan' => $totalScan]);
    }


    public function saveFileForShipmentEvidence(Request $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            if ($request['shipmentid'] == null || $request['file'] == null) {
                return $this->getResponseWithErrors('saveFileForShipmentEvidence',
                    'El número de embarque y Foto son valores obligatorios.');
            }
            $shipment = StorageEvidenceProcess::controlAccessUser()
                ->where('shipmentid', '=', $request['shipmentid'])->first();
            if (empty($shipment)) {
                return $this->getResponseWithErrors('saveFileForShipmentEvidence', 'No existen datos del embarque.');
            }
            if ($shipment->statusid != 'E') {
                return $this->getResponseWithErrors('saveFileForShipmentEvidence', 'El embarque se encuentra termiando.');
            }

            $path = $this->saveFile($request, $shipment);
            $this->createEvidencePhoto(
                'StorageEvidenceProcess',
                $shipment->recordid,
                $path,
                'SH',
                $shipment
            );
            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('saveFileForShipmentEvidence', []);
        } else {
            return $this->getResponseWithErrors('saveFileForShipmentEvidence', 'Error al procesar la solicitud');
        }
    }


    public function validateRequestPartnumber(StorageEvidenceProcess $shipment, \App\Models\Inventory\PartNumber\InventoryPartnumber $partnumberid)
    {
        return StorageEvidenceStock::controlAccessUser()
            ->where('shipmentid', '=', $shipment->recordid)
            ->where('partnumberid', '=', $partnumberid->id)->first();
    }

    /**
     * @param StorageEvidenceProcess $shipment
     * @param \App\Models\Inventory\PartNumber\InventoryPartnumber $partnumberid
     * @return array
     */
    public function getTotalCheckPartNumber(StorageEvidenceProcess $shipment, \App\Models\Inventory\PartNumber\InventoryPartnumber $partnumberid): array
    {
        return DB::select(
            "select sum(quantity) as verificado
                        from storageevidencecontainers
                        where shipmentid=? and partnumberid=? "
            , [$shipment->recordid, $partnumberid->id]);
    }

}
