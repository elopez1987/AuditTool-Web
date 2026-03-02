<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Evidence\StorageEvidenceProcessRequest;
use App\Imports\Evidence\Plan\ImportRequestShipment;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Evidence\EvidenceContainer;
use App\Models\Evidence\StorageEvidenceProcess;
use App\Models\General\EvidencePhoto;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Traits\Web\Evidence\QueryStorageEvidences;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StorageEvidenceProcessesController extends BaseController
{
    use QueryStorageEvidences;

    public function index()
    {
        $this->authorize('storageevidenceprocess.index');
        $status = SystemStatus::whereIn('statusid', ['E', 'D'])
            ->select(['statusid', 'name'])->get();
        $customers = $this->getOrderCustomerForSelect();
        $turns = $this->getTurns();
        $categories = InventoryStockCategory::select(['id', 'description'])->get();

        return view('Evidence.StorageEvidences.index',
            compact('status', 'customers', 'turns', 'categories'));
    }

    public function getEvidencesProcess(Request $request)
    {
        $data = $this->getAllEvidencesShipments($request);

        return $this->showDataToView($data,
            'Evidence.StorageEvidences.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storageevidenceprocess.create');
        $providers = $this->getReceiptProviderForSelect();
        $customers = $this->getOrderCustomerForSelect();
        $locations = $this->getLocationActiveIN();
        $carriers = $this->getCarriersForSelect();
        $turns = $this->getTurns();
        $categories = $this->getCategoriesInventory();
        $types = $this->getRasonForCodes([1, 2]);

        return view('Evidence.StorageEvidences.create',
            compact('providers',
                'customers', 'types', 'carriers',
                'locations', 'turns', 'categories'));
    }

    public function store(StorageEvidenceProcessRequest $request)
    {
        $access = $this->getAccessForUser();
        $shipment = new StorageEvidenceProcess(
            $request->all() +
            ['centerid' => $access->centerid] +
            ['warehouseid' => $access->warehouseid]
        );
        $shipment->save();

        return redirect('storageevidenceprocess/' . $shipment->recordid);
    }

    public function show(StorageEvidenceProcess $storageevidenceprocess)
    {
        $this->authorize('storageevidenceprocess.show');
        $lpns = EvidenceContainer::where('shipmentid', $storageevidenceprocess->recordid)->get();
        $stocks = $this->getTotalVerificado($storageevidenceprocess->recordid);
        $evidences = $this->getEvidencePhotosForShipment($storageevidenceprocess->recordid);

        return view('Evidence.StorageEvidences.show',
            compact('storageevidenceprocess', 'lpns', 'evidences', 'stocks'));
    }

    public function edit(StorageEvidenceProcess $storageevidenceprocess)
    {
        $this->authorize('storageevidenceprocess.edit');
        $providers = $this->getReceiptProviderForSelect();
        $customers = $this->getOrderCustomerForSelect();
        $locations = $this->getLocationActiveIN();
        $carriers = $this->getCarriersForSelect();
        $turns = $this->getTurns();
        $categories = $this->getCategoriesInventory();
        $types = $this->getRasonForCodes([1, 2]);

        return view('Evidence.StorageEvidences.edit',
            compact('storageevidenceprocess',
                'providers', 'customers', 'types', 'carriers',
                'locations', 'turns', 'categories'));
    }

    public function update(Request $request, StorageEvidenceProcess $storageevidenceprocess)
    {
        $storageevidenceprocess->update($request->all());

        return $this->redirectBackSuccessMessage('Modificación realizada con exito.');
    }

    public function destroy(StorageEvidenceProcess $storageevidenceprocess)
    {
        DB::beginTransaction();
        try {
            $files = $this->getPhotosForEvidences($storageevidenceprocess);
            if (! empty($files)) {
                foreach ($files as $file) {
                    $photo = EvidencePhoto::findOrFail($file->id);
                    Storage::delete($photo->path);
                    EvidencePhoto::destroy($file->id);
                }
            }
            $storageevidenceprocess->delete();
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->successResponse('storageevidenceprocess.index');
        } else {
            return $this->errorResponse('Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }

    public function downloadfiles($id)
    {
        $shipmentEvidence = StorageEvidenceProcess::findOrFail($id);
        $zip = new \ZipArchive;
        $zip->open($shipmentEvidence->shipmentid . '.zip', \ZipArchive::CREATE);
        $dir = 'Evidencias';
        $zip->addEmptyDir($dir);

        $files = EvidenceContainer::where('shipmentid', '=', $shipmentEvidence->recordid)
            ->select('filecheck')->get();

        foreach ($files as $file) {
            $filepath = storage_path() . '/app/' . $file->filecheck;
            $zip->addFile($filepath, $dir . '/' . $file->filecheck);
        }

        $zip->close();
        header('Content-type: application/octet-stream');
        header("Content-disposition: attachment; filename=$shipmentEvidence->shipmentid.zip");
        readfile($shipmentEvidence->shipmentid . '.zip');
        unlink($shipmentEvidence->shipmentid . '.zip');
    }

    public function importrequestshipmenttoload(Request $request)
    {
        Excel::import(new ImportRequestShipment($request['recordid']),
            $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }

    public function dashboard()
    {
        $this->authorize('storageevidenceprocess.dashboard');

        return view('Evidence.Dashboards.Shipments.index');
    }

    public function getDataToDashboardShipmentProcess(Request $request)
    {
        $shipments = $this->getShipmentForDashboard($request);

        return response()->json(['data' => $shipments, 'status' => 'success']);
    }
}
