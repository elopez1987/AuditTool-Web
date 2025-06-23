<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Http\Services\Shipment\IncidecesJourney\ServiceIncidenceJourney;
use App\Http\Services\Shipment\IncidecesJourney\ServiceIncidenceJourneyDetail;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Traits\IncidenceJourney;
use App\Traits\Web\Shipments\QuerysIncidencesJourneys;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;

class StorageIncidenceJourneysController extends BaseController
{

    use QuerysIncidencesJourneys;

    public function index()
    {
        $this->authorize('storageincidencesjourneys.index');
        return view('Shipments.StorageIncidenceJourneys.index');
    }

    public function getDataIncidenceShipments()
    {
        $data = $this->getAllIncidenceJourneys();
        return DataTables::collection($data)
            ->addColumn('btn', "Shipments.StorageIncidenceJourneys.partials.buttons")
            ->addColumn('time', "Shipments.StorageIncidenceJourneys.partials.duration")
            ->rawColumns(['btn', 'time'])->toJson();
    }

    public function create()
    {
        $this->authorize('storageincidencesjourneys.create');
        $shipments = $this->getShipmentsForCreateNewIncidence();
        $razoncodes = $this->getCategoriesIncidencesDetail();
        return view('Shipments.StorageIncidenceJourneys.create',
            compact('shipments', 'razoncodes'));
    }

    public function store(Request $request, ServiceIncidenceJourney $serviceIncidenceJourney)
    {
        DB::beginTransaction();
        try {
            $shipment = OrderDeliverie::findOrFail($request['shipmentid']);
            $partnumberid = InventoryPartnumber::controlAccessUser()->where('prtnum', $request['partnumberid'])->first();
            if (empty($partnumberid)) {
                return response()->json([
                    'message' => 'Numero de Sku no existe, favor de verificar.',
                ], 422);
            }

            $statusSku = StorageIncidenceJourneyDetails::controlAccessUser()->where('partnumberid', $request['partnumberid'])
                ->where('orderid', $shipment->id)->where('delivery', $request['delivery'])->first();
            if (!empty($statusSku)) {
                return response()->json([
                    'message' => 'Numero de Sku y factura ya se encuentran registrados.',
                ], 422);
            }

            $incidence = $serviceIncidenceJourney->addNewIncidence($shipment);
            $serviceIncidenceJourney->addDetailToIncidence($request, $incidence);
            DB::commit();
            return response()->json(['message' => 'Incidencia registrado con exito', 200]);
        } catch (Exception) {
            DB::rollback();
            return response()->json(['message' => 'Error al registrar la incidencia,favor de verificar los datos', 422]);
        }
    }

    public function show($folioid, ServiceIncidenceJourneyDetail $serviceIncidenceJourneyDetail)
    {
        $this->authorize('storageincidencesjourneys.show');
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $details = $serviceIncidenceJourneyDetail->getDetailResponseIncidence($shipment->orderid);
        $evidences = EvidencePhoto::getPhotosForModel($folioid, 'StorageIncidenceJourney');
        $entregas = EvidencePhoto::where('model_id', $folioid)
            ->where('model_type', '=', 'StorageIncidenceJourney')
            ->whereIn('auxiliary01', ['ENT', 'INC'])->get();
        $comments = StorageComment::getCommentForModel($shipment->folioid, 'StorageIncidenceJourney');
        return view('Shipments.StorageIncidenceJourneys.show',
            compact('shipment', 'details', 'evidences', 'entregas', 'comments'));
    }

    public function edit($folioid)
    {
        $this->authorize('storageincidencesjourneys.edit');
        $razoncodes = StorageCategoryIncidenceDetail::controlAccessUser()
            ->where('auxiliary01', '=', 'SHP')
            ->select(['description', 'categoryincdetailid'])->get();
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $details = StorageIncidenceJourneyDetails::getDetailOfIncidenceForOrder($shipment->orderid);
        return view('Shipments.StorageIncidenceJourneys.edit',
            compact('shipment', 'details', 'razoncodes'));
    }

    public function destroy($folioid)
    {
        $folio = StorageIncidenceJourneyDetails::findOrFail($folioid);
        $folio->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito');
    }


}
