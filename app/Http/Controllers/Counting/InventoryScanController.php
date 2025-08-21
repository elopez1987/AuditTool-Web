<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inventory\FiscalApp\SaveCountAppRequest;
use App\Http\Services\Counting\ServiceInventoryCount;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Traits\ResponseApi;
use App\Traits\Web\Counting\QueryInventoryScan;
use App\Traits\Web\General\EvidencePhotoTrait;
use App\Traits\Web\General\GetParameters;
use Auth;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Validator;

class InventoryScanController extends Controller
{
    use ResponseApi;
    use QueryInventoryScan;
    use EvidencePhotoTrait;
    use GetParameters;

    private ServiceInventoryCount $serviceInventoryCount;

    public function __construct(ServiceInventoryCount $serviceInventoryCount)
    {
        $this->serviceInventoryCount = $serviceInventoryCount;
    }

//    Lista los inventarios activos de tipo App
    public function getInventoryActive()
    {
        $data = InventoryHead::controlAccessUser()->where('statusid', 'AT')
            ->where('inventorytype', 'APP')
            ->select(['id as inventoryid', 'description'])
            ->get();
        return $this->getResponseSuccess('getInventoryActive', $data);
    }

    public function getInventoryActiveDron()
    {
        $data = InventoryHead::controlAccessUser()->where('statusid', 'AT')
            ->where('inventorytype', 'DRON')
            ->select(['id as inventoryid', 'description'])
            ->get();
        return $this->getResponseSuccess('getInventoryActive', $data);
    }

//    Busca la cudula a contar
    public function getDataCard(Request $request)
    {
        if (empty($request['cardid']) || empty($request['countid']) || empty($request['inventoryid'])) {
            return $this->getResponseWithErrors('getDataCard', 'Todos los datos son obligatorios.');
        }
        $card = $this->getCard($request);
        if (empty($card)) {
            return $this->getResponseWithErrors('getDataCard', 'La Cédula ingresada no existe en el inventario seleccionado.');
        }
        if ($request['countid'] == 'Primero') {
            if (!empty($card->finishdate01)) {
                return $this->getResponseWithErrors('getDataCard', 'La cédula ya se encuentra finalizada para el conteo 1.');
            }
            if (!empty($card->startdate01)) {
                if ($card->responsible01 != Auth::user()->username) {
                    return $this->getResponseWithErrors('getDataCard', 'El primer conteo ya fue iniciado.');
                } else {
                    return $this->getResponseSuccessCard($card);
                }
            }
            if (!empty($card->startdate02)) {
                if ($card->responsible02 == Auth::user()->username) {
                    return $this->getResponseWithErrors('getDataCard', 'El conteo no puede ser iniciado por el mismo  usuario!!');
                }
            }
            $this->serviceInventoryCount->startCount01($card);
        }
        if ($request['countid'] == 'Segundo') {
            if (!empty($card->finishdate02)) {
                return $this->getResponseWithErrors('getDataCard', 'La cédula ya se encuentra finalizada para el conteo 2.');
            }
            if ($card->responsible01 == Auth::user()->username) {
                return $this->getResponseWithErrors('getDataCard', 'El conteo no puede ser iniciado por el mismo  usuario!!');
            }
            if (!empty($card->startdate02)) {
                if ($card->responsible02 != Auth::user()->username) {
                    return $this->getResponseWithErrors('getDataCard', 'El segundo conteo ya fue iniciado.');
                } else {
                    return $this->getResponseSuccessCard($card);
                }
            }
            $this->serviceInventoryCount->startCount02($card);
        }
        return $this->getResponseSuccessCard($card);
    }


//    Busca el número de parte para el conteo
    public function getDataSkuForInventory(Request $request)
    {
        if ($request['partnumberid'] == '') {
            return $this->getResponseWithErrors('getDataSkuForInventory', 'El número de parte es un valor obligatorio.');
        }
        $sku = $this->getPartnumber($request['partnumberid']);
        if (empty($sku)) {
            return $this->getResponseWithErrors('getDataSkuForInventory', 'El número de parte no existe.');
        }
        return $this->getResponseSuccess('getDataSkuForInventory', $sku);
    }


//    Consulta la ubicacion a contar
    public function getValidateLocation(Request $request)
    {
        $location = $this->getLocation($request);
        if (empty($location)) {
            return $this->getResponseWithErrors('getValidateLocation', 'La ubicación ingresada no existe en el conteo.');
        }
        if ($location->cardid != trim(strtoupper($request['cardid']))) {
            return $this->getResponseWithErrors('getValidateLocation', 'La ubicación no corresponde a la cédula.');
        }
        if ($request['countid'] == 'Primero') {
            if ($location->statusid01 == 'TO') {
                return $this->getResponseWithErrors('getValidateLocation', 'La ubicación ya fue contada.');
            }
        }
        if ($request['countid'] == 'Segundo') {
            if ($location->statusid02 == 'TO') {
                return $this->getResponseWithErrors('getValidateLocation', 'La ubicación ya fue contada.');
            }
        }
        return $this->getResponseSuccess('getValidateLocation', $location);
    }


//    Guarda el conteo fiscal
    public function saveCountScanLocation(SaveCountAppRequest $request)
    {
        DB::beginTransaction();
        try {
            $inventory = InventoryHead::where('id', $request['inventoryid'])->first();
            $parameterApp = $this->getParametersApp($inventory->centerid);
            if (!empty($parameterApp) && in_array('APP-11', (array)$parameterApp)) {
                $validator = Validator::make($request->all(), [
                    'quantitylpn' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return $this->getResponseWithErrors('saveCountScanLocation', 'La cantidad de Lpns debe ser entero.');
                }
            }
            $location = $this->getLocation($request);
            if ($request['partnumberid'] != '0') {
                $sku = $this->getPartnumber($request['partnumberid']);
                if (empty($sku)) {
                    return $this->getResponseWithErrors('saveCountScanLocation', 'El número de parte no existe.');
                }
                if ($request['quantity'] <= 0) {
                    return $this->getResponseWithErrors('saveCountScanLocation', 'La cantidad debe ser mayor a 0, favor de validar');
                }
            }
            if ($location->cardid != trim(strtoupper($request['cardid']))) {
                return $this->getResponseWithErrors('saveCountScanLocation', 'La ubicación no corresponde a la cédula.');
            }
            if ($request['countid'] == 'Primero') {
                $this->serviceInventoryCount->createCount01($location, $request);
                $this->serviceInventoryCount->updateLocation($location, 'statusid01');
                $pending = $this->getCountPendingForCard($location, 'statusid01');
            }
            if ($request['countid'] == 'Segundo') {
                $this->serviceInventoryCount->createCount02($location, $request);
                $this->serviceInventoryCount->updateLocation($location, 'statusid02');
                $pending = $this->getCountPendingForCard($location, 'statusid02');
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();
        }
        if ($success) {
            if ($pending == 0) {
                return $this->getResponseSuccessWithMessage('saveCountScanLocation', '', 'Ya no tiene ubicaciones pendientes de contar, favor de cerrar la cedula.', '1');
            } else {
                return $this->getResponseSuccessWithMessage('saveCountScanLocation', '', 'Conteo registrado con exito.', '0');
            }
        } else {
            return $this->getResponseWithErrors('saveCountScanLocation', 'Error al procesa la solicitud.');
        }
    }

//    Cierra el conteo de la cedula
    public function closeCardCount(Request $request)
    {
        if ($request['inventoryid'] == null || $request['cardid'] == null || $request['countid'] == null) {
            return $this->getResponseWithErrors('saveCountScanLocation', 'El inventario,cédula  y tipo de conteo son obligatorios.');
        }
        $card = $this->getCard($request);
        if ($request['countid'] == 'Primero') {
            $total = $this->getCountPendingForCard($card, 'statusid01');
            if ($total > 0) {
                $locations = $this->getLocationPendingCard($card, 'statusid01');
                return $this->getResponseWithErrorsData('closeCardCount', 'La cédula tiene ubicaciones pendientes de contar.', $locations);
            }
        }
        if ($request['countid'] == 'Segundo') {
            $total = $this->getCountPendingForCard($card, 'statusid02');
            if ($total > 0) {
                $locations = $this->getLocationPendingCard($card, 'statusid02');
                return $this->getResponseWithErrorsData('closeCardCount', 'La cédula tiene ubicaciones pendientes de contar.', $locations);
            }
        }
        return $this->getResponseSuccess('closeCardCount', $card);
    }

    public function closeCardWithKeys(Request $request)
    {
        if ($request['inventoryid'] == null || $request['cardid'] == null || $request['countid'] == null || $request['file'] == null) {
            return $this->getResponseWithErrors('saveCountScanLocation', 'La firma es un valor obligatorio.');
        }
        DB::beginTransaction();
        try {
            $card = $this->getCard($request);
            $path = $this->saveFileToModelBase64($request['file'], 'InventoryCard', $card, 'EvidenceFiscal', 'EVI', 'Table');
            if ($request['countid'] == 'Primero') {
                $card->finishdate01 = now();
                $card->keycount01 = $path;
            } elseif ($request['countid'] == 'Segundo') {
                $card->finishdate02 = now();
                $card->keycount02 = $path;
            }
            $card->save();
            DB::commit();
            return $this->getResponseSuccess('saveCountScanLocation', $card);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrorsData('saveCountScanLocation', 'Error al cerrar la cedula.', $card);
        }
    }

//    Firma de cedulas de conteos de dron
    public function getCardForKeyDron(Request $request)
    {
        if ($request['inventoryid'] == null) {
            return $this->getResponseWithErrors('saveCountScanLocation', 'El número de inventario es un valor obligatorio.');
        }
        $cards = InventoryCard::where('inventoryid', $request['inventoryid'])
            ->where('responsible01', Auth::user()->username)
            ->whereNull('finishdate01')
            ->select(['centerid', 'inventoryid', 'cardid', 'finishdate01', 'responsible01', 'recordid'])
            ->get();
        return $this->getResponseSuccess('getCardForKeyDron', $cards);
    }

//    ----------------------------------

    /**
     * @param InventoryCard $card
     * @return JsonResponse
     */
    public function getResponseSuccessCard(InventoryCard $card): JsonResponse
    {
        return $this->getResponseSuccess('getDataCard', [
            'inventory' => $card->inventoryid,
            'cardid' => $card->cardid,
            'unitmeasureid' => $card->unitmeasureid,
            'startdate01' => $card->startdate01,
            'finishdate01' => $card->finishdate01,
            'responsible01' => $card->responsible01,
            'startdate02' => $card->startdate02,
            'finishdate02' => $card->finishdate02,
            'responsible02' => $card->responsible02,
        ]);
    }


}
