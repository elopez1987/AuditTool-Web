<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\EvidencePhotoTrait;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FinishProcessUnitController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use EvidencePhotoTrait;
    use CheckListTransportTrait;

    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['barcodebox'] == null) {
                return $this->getResponseWithErrors('finishUnitProcess', 'El codigo de barra de la caja es obligatorio.');
            }
            $folionumber = ReceiptUnit::controlAccessUser()
                ->where('barcode', '=', $request['barcodebox'])
                ->whereNotIn('statusid', ['TR', 'OU', 'FI'])->first();
            if (empty($folionumber)) {
                return $this->getResponseWithErrors('finishUnitProcess', 'No existe el número de folio buscado, favor de validar.');
            }
            $parameter = $this->getParametersOfWarehouse($folionumber->warehouseid);
            if (!empty($parameter) && in_array('SWH-39', $parameter)) {
                if ($folionumber->startdate == null) {
                    return $this->getResponseWithErrors('startUnitProcess', 'La unidad no cuenta con evento de inicio de proceso, favor de validar.');
                }
            }
            $parameterApp = $this->getParametersApp($folionumber->centerid);
            ReceiptUnit::updateStatusForFinishProcess($folionumber);
            StorageUnitTrace::createTraceUnit($folionumber, '15');
//          Recibo
            if ($folionumber->razonstatusid == 2) {
                ReceiptAsn::finishProcessReceipt($folionumber->id);
            }
//          Embarque
            if ($folionumber->razonstatusid == 1) {
                $this->updateOrderDelivery($folionumber);
            }
//            Registrar los datos en el fin de proceso de carga o descarga
            if (!empty($parameterApp) && in_array('APP-08', (array)$parameterApp)) {
                $validator = \Validator::make($request->all(), [
                    'supervisorname' => 'required|max:50',
                    'nameguard02' => 'required|max:50',
                    'keyguard02' => 'required',
                    'supervisorkey' => 'required',
                    'checklitsdetailid' => 'required|array',
                    'response' => 'required|array',
                    'checklistid' => 'required|array'
                ]);
                if ($validator->fails()) {
                    return $this->getResponseWithErrors('finishUnitProcess', 'Todos los valores son obligatorios.');
                }
                list($keyGuard, $keySupervisor) = $this->saveKeysSupervisorAndGuard($request['keyguard02'],$request['supervisorkey'], $folionumber);
                ReceiptUnitKey::updateOrCreate([
                    'centerid' => $folionumber->centerid,
                    'warehouseid' => $folionumber->warehouseid,
                    'folionumber' => $folionumber->id,
                ], [
                    'containerseal' => $request['containerseal'],
                    'supervisorname' => $request['supervisorname'],
                    'nameguard02' => $request['nameguard02'],
                    'keyguard02' => $keyGuard,
                    'supervisorkey' => $keySupervisor,
                ]);
                $this->clearCheckListTransport($folionumber->id, 'QI');
                $this->createCheckListToUnitV2($request, $folionumber, 'QI');
            }
//          Foto de caja vacia al fin del proceso
            if (!empty($parameterApp) && in_array('APP-05', (array)$parameterApp)) {
                if (empty($request['file'])) {
                    return $this->getResponseWithErrors('finishUnitProcess', 'La foto de la caja vacia es obligatoria.');
                }
                $this->saveFileToModelBase64($request['file'], 'ReceiptUnit', $folionumber, 'EvidenceOut', 'OUT', 'ModelEvidence');
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getTraceAsString();
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('finishUnitProcess', '');
        } else {
            return $this->getResponseWithErrors('finishUnitProcess', 'Error al procesar el movimiento');
        }
    }

    /**
     * @param ReceiptUnit $folionumber
     * @return void
     */
    public function updateOrderDelivery(ReceiptUnit $folionumber): void
    {
        $parameter = $this->getParametersOfWarehouse($folionumber->warehouseid);
        $shipment = OrderDeliverie::controlAccessUser()->where('folionumber',$folionumber->id)->first();
        if (!empty($shipment)) {
            OrderDeliverie::updateStatusForFinishProcess($shipment);
            if (in_array('SWH-21', (array)$parameter)) {
                EdiShipment::createEDIForEventToShipment($shipment,'END_LOAD');
            }
        }
    }

    /**
     * @param $keyguard
     * @param $folionumber
     * @return string[]
     */
    public function saveKeysSupervisorAndGuard($keyguard,$supervisorkey, $folionumber): array
    {
        $image = $keyguard;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $folionumber->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $folionumber->centerid . '/EvidenceIn/' . $imageName), base64_decode($image));
        $keyGuard = 'public/' . $folionumber->centerid . '/EvidenceIn/' . $imageName;

        $image = $supervisorkey;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $folionumber->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $folionumber->centerid . '/EvidenceIn/' . $imageName), base64_decode($image));
        $keySupervisor = 'public/' . $folionumber->centerid . '/EvidenceIn/' . $imageName;
        return array($keyGuard, $keySupervisor);
    }


}
