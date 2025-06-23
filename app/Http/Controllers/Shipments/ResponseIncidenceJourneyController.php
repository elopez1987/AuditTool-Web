<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Http\Services\Shipment\IncidecesJourney\ServiceIncidenceJourneyDetail;
use App\Http\Services\Shipment\IncidecesJourney\ServiceResponseIncidences;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use DB;
use Illuminate\Http\Request;

class ResponseIncidenceJourneyController extends BaseController
{


    public function viewresponse($folioid)
    {
        $this->authorize('storageincidencesjourneys.response');
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $details = StorageIncidenceJourneyDetails::getDetailOfIncidenceForOrder($shipment->orderid);
        $status = SystemStatus::where('schemaid', 'EIJ')
            ->whereIn('statusid', ['AB', 'CD'])
            ->select(['systemstatus.name', 'statusid'])->get();
        $statusDetails = SystemStatus::where('schemaid', 'EIJ')
            ->whereIn('systemstatus.statusid', ['P', 'N'])
            ->select(['name', 'statusid'])->get();
        $responsable = StorageCategoryIncidenceDetail::where('auxiliary01', 'INC')->where('statusid','E')
            ->select(['categoryincdetailid', 'description'])->get();
        $entregas = EvidencePhoto::where('model_id', $folioid)
            ->where('model_type', '=', 'StorageIncidenceJourney')
            ->whereIn('auxiliary01', ['ENT', 'INC'])->get();
        $comments = StorageComment::getCommentForModel($shipment->folioid,'StorageIncidenceJourney');
        return view('Shipments.StorageIncidenceJourneys.response',
            compact('shipment', 'details', 'status',
                'statusDetails', 'responsable', 'entregas', 'comments'));
    }

    public function responsestore(
        $folioid, Request $request,
        ServiceResponseIncidences $serviceResponseIncidences,
        ServiceIncidenceJourneyDetail $serviceIncidenceJourneyDetail)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $serviceResponseIncidences->updateStatusIncidence($folioid, $request['statusid']);
            $serviceIncidenceJourneyDetail->updateDetailIncidence($request);
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->successResponse('storageincidencesjourneys.index');
        } else {
            return $this->errorResponse('Error al procesa la solicitud : ' . $error);
        }
    }

    public function uploadEvidenceForIncidences(Request $request, $folioid)
    {
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $pathdb = EvidencePhoto::uploadFileToServer($request, $shipment->centerid, 'EvidenceShipment');
        if ($pathdb) {
            EvidencePhoto::registerEvidence('StorageIncidenceJourney', $folioid, $pathdb, 'INC', $shipment);
        }
    }


    public function searchPhotoInShipment($folioid)
    {
        $this->authorize('searchPhotoInShipment');
        $incidence = StorageIncidenceJourney::searchIncidence($folioid);
        $order = OrderDeliverie::controlAccessUser()->where('id', '=', $incidence->orderid)
            ->select(['centerid', 'warehouseid', 'id'])->first();
        $photos = EvidencePhoto::where('model_type', '=', 'OrderDelivery')
            ->where('model_id', '=', $order->id)
            ->where('auxiliary01', '=', 'SH')
            ->select(['id', 'path'])->get();

        foreach ($photos as $photo) {
            EvidencePhoto::updateOrCreate([
                'model_type' => 'StorageIncidenceJourney',
                'model_id' => $folioid,
                'path' => $photo->path,
            ], [
                'createuser' => \Auth::user()->id,
                'auxiliary01' => 'INC',
                'auxiliary02' => $order->centerid,
                'auxiliary03' => $order->warehouseid,
            ]);
        }
        return $this->redirectBackSuccessMessage('Evidencias vinculas con exito.');
    }
}
