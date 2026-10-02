<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\TraceUnit;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\CheckListEvidenceProcessUnit;
use App\Models\Yard\CheckListTransport;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitSeal;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageLoto\StorageTraceLoto;
use App\Models\Yard\StorageUnitTrace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class TraceReceiptUnitController extends BaseController
{
    public function index()
    {
        $this->authorize('tracereceiptunit.index');
        $warehouses = $this->getWarehouseForSelect();
        $concepts = $this->typeReasonInActiveToSelect();
        $carriers = $this->getCarriersForTransport();

        return view('Yards.KardexReceiptUnit.KardexForFolio.index',
            compact('warehouses', 'concepts', 'carriers'));
    }

    public function getDataReceiptUnitToKardex(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,name', 'warehouse:warehouseid,name', 'reason:id,name', 'status:statusid,name'])
            ->select([
                'receiptunits.id',
                'receiptunits.warehouseid',
                'receiptunits.barcode',
                'receiptunits.carrierid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.trucknumber',
                'receiptunits.operatorname',
                'receiptunits.shipmentid',
                'receiptunits.containerseal',
                'receiptunits.razonstatusid',
                'receiptunits.entrydate',
                'receiptunits.statusid',
            ])
            ->Folio($request->get('folionumber'))
            ->Shipmentid($request->get('shipmentid'))
            ->BoxNumber($request->get('boxnumber'))
            ->Code($request->get('barcode'))
            ->Warehouse($request->get('warehouseid'))
            ->Rason($request->get('razonstatusid'))
            ->Carrier($request->get('carrierid'))
            ->EcoNumber($request->get('ecobox'))
            ->when($request->filled('startDate') && $request->filled('endDate'), function ($query) use ($request) {
                $query->whereBetween('receiptunits.entrydate', [
                    Carbon::parse($request->get('startDate'))->startOfDay(),
                    Carbon::parse($request->get('endDate'))->endOfDay(),
                ]);
            });

        return $this->showDataToView($data, 'Yards.KardexReceiptUnit.KardexForFolio.buttons');
    }

    public function show($id)
    {
        $receiptunit = ReceiptUnit::with(['warehouse', 'key'])->findOrFail($id);

        $traces = StorageUnitTrace::controlAccessUser()
            ->with(['event', 'carrier', 'unit', 'unitstatus', 'zone', 'location', 'user'])
            ->where('receiptunitid', '=', $id)
            ->get();

        $evidences = EvidencePhoto::where('model_id', $id)
            ->leftJoin('systemstatus', 'storageevidencephotos.auxiliary04', '=', 'systemstatus.statusid')
            ->where('model_type', '=', 'ReceiptUnit')
            ->with('user')
            ->select(['storageevidencephotos.*', 'systemstatus.name as tipo'])->get();

        $evidencesTiros = CheckListEvidenceProcessUnit::where('modelid', $receiptunit->id)
            ->whereNotNull('path')->where('modeltype', 'ReceiptUnits')
            ->with('user')
            ->get();

        $checklisttransports = $this->getChecklistTransports($receiptunit);

        $comments = StorageComment::where('modeltype', 'ReceiptUnits')
            ->where('modelid', $id)
            ->with('user')
            ->get();

        $seals = ReceiptUnitSeal::controlAccessUser()
            ->where('folionumber', '=', $id)
            ->with('user')
            ->get();

        $peso = ReceiptUnitTicket::where('folionumber', '=', $id)->first();

        $lotos = StorageTraceLoto::controlAccessUser()
            ->where('folionumber', '=', $id)
            ->with(['loto', 'event', 'user'])
            ->get();

        return view(
            'Yards.KardexReceiptUnit.KardexForFolio.show',
            compact(
                'traces',
                'evidences',
                'comments',
                'checklisttransports',
                'receiptunit',
                'evidencesTiros',
                'seals',
                'peso',
                'lotos'
            )
        );
    }

    //	Eventos
    private function getChecklistTransports($receiptunit)
    {
        return CheckListTransport::where('receiptunitid', $receiptunit->id)
            ->where('centerid', $receiptunit->centerid)
            ->with('checklistdetail')
            ->get();
    }

    public function generateinvoicekardex($id)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $id)->first();

        $evidences = EvidencePhoto::where('model_id', $id)
            ->where('model_type', '=', 'ReceiptUnit')
            ->leftJoin('systemstatus', 'storageevidencephotos.auxiliary04', '=', 'systemstatus.statusid')
            ->select(['storageevidencephotos.*', 'systemstatus.name as tipo'])->get();

        $evidencesCTPAT = CheckListTransport::controlAccessUser()->where('receiptunitid', $id)
            ->whereNotNull('photo_1')
            ->get();

        $evidencesTiros = CheckListEvidenceProcessUnit::where('modelid', $receiptunit->id)
            ->whereNotNull('path')->where('modeltype', 'ReceiptUnits')->get();

        $comments = StorageComment::where('modeltype', 'ReceiptUnits')
            ->where('modelid', $id)->get();
        $traces = StorageUnitTrace::controlAccessUser()->where('receiptunitid', '=', $id)->get();

        $fortmat = 'Yards.KardexReceiptUnit.Formats.Kardex';
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact(
            'receiptunit',
            'evidences',
            'traces',
            'evidencesTiros',
            'comments',
            'evidencesCTPAT'

        ));

        return $pdf->download('Kardex de unidad -' . $id . '.pdf');
    }

    public function printchecklistbrind($id)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $id)->first();
        $tracto = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $verified = DB::table('storagechecklisttransports as s')
            ->select(
                's.id',
                's.centerid',
                's.checklistid',
                's.receiptunitid',
                's.checklitsdetailid',
                's.verified',
                's.type',
                'ch.auxiliary01 as idcheck',
                'ch.description',
            )
            ->leftJoin('storagechecklistdetails as ch', function ($join) {
                $join->on('s.checklistid', '=', 'ch.checklistid')->on('s.checklitsdetailid', '=', 'ch.id');
            })
            ->where('s.receiptunitid', $receiptunit->id)
            ->whereIn('s.type', ['IN', 'QI'])
            ->get();

        $verifiedOut = DB::table('storagechecklisttransports as s')
            ->select(
                's.id',
                's.centerid',
                's.checklistid',
                's.receiptunitid',
                's.checklitsdetailid',
                's.verified',
                's.type',
                'ch.auxiliary01 as idcheckOut',
                'ch.description',
            )
            ->leftJoin('storagechecklistdetails as ch', function ($join) {
                $join->on('s.checklistid', '=', 'ch.checklistid')->on('s.checklitsdetailid', '=', 'ch.id');
            })
            ->where('s.receiptunitid', $receiptunit->id)
            ->whereIn('s.type', ['OUT'])->get();
        $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.InvoiceBridgestonePT';
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact('receiptunit', 'verified', 'tracto', 'verifiedOut'));

        return $pdf->download('Folio_De_Entrada-' . $receiptunit->id . '.pdf');
    }

    public function printqualitybridge($id)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $id)->first();
        $comments = StorageComment::where('modeltype', 'ReceiptUnits')->where('modelid', $id)->get();
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $verified = DB::table('storagechecklisttransports as s')
            ->select(
                's.id',
                's.centerid',
                's.checklistid',
                's.receiptunitid',
                's.checklitsdetailid',
                's.verified',
                's.type',
                'ch.auxiliary01 as idcheck',
                'ch.description',
            )
            ->leftJoin('storagechecklistdetails as ch', function ($join) {
                $join->on('s.checklistid', '=', 'ch.checklistid')->on('s.checklitsdetailid', '=', 'ch.id');
            })
            ->where('s.receiptunitid', $receiptunit->id)
            ->whereIn('s.type', ['QI', 'AS'])
            ->get();
        $photos = EvidencePhoto::where('model_id', $id)
            ->where('model_type', 'ReceiptUnit')->where('auxiliary01', 'QI')->get();
        $fortmat = 'Yards.KardexReceiptUnit.Formats.CalidaBridgestone';
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])
            ->loadView($fortmat, compact('receiptunit', 'comments', 'verified', 'photos'));

        return $pdf->download('Folio_De_Calidad-' . $receiptunit->id . '.pdf');
    }

    public function downloadFiles($id)
    {
        $receiptUnit = ReceiptUnit::controlAccessUser()->findOrFail($id);

        $files = EvidencePhoto::where('model_type', 'ReceiptUnit')
            ->where('model_id', $receiptUnit->id)
            ->get();

        $evidencesCTPAT = CheckListTransport::controlAccessUser()
            ->where('receiptunitid', $receiptUnit->id)
            ->get();

        // Unificamos ambas colecciones en una sola lista de rutas de archivo.
        // Asumo que CheckListTransport también expone un atributo `path` (igual que EvidencePhoto).
        // Si el campo se llama distinto, ajusta el `pluck`/`map` correspondiente.
        $allFiles = $files->pluck('path')
            ->merge($evidencesCTPAT->pluck('photo_1'))
            ->filter(); // descarta null/vacíos

        if ($allFiles->isEmpty()) {
            return $this->errorResponse('No hay archivos para descargar.');
        }

        $zipFileName = $receiptUnit->id . '_' . uniqid() . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        if (! file_exists(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0755, true);
        }

        $zip = new \ZipArchive;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['message' => 'No se pudo crear el archivo ZIP.'], 500);
        }

        $addedAny = false;
        $usedNames = [];

        foreach ($allFiles as $path) {
            $filepath = storage_path('app/' . $path);

            if (! file_exists($filepath)) {
                continue;
            }

            $newPath = basename($path);

            // Evita sobrescribir dentro del zip si EvidencePhoto y CheckListTransport
            // llegaran a tener archivos con el mismo nombre.
            if (isset($usedNames[$newPath])) {
                $usedNames[$newPath]++;
                $newPath = pathinfo($newPath, PATHINFO_FILENAME) . "_{$usedNames[$newPath]}." . pathinfo($newPath, PATHINFO_EXTENSION);
            } else {
                $usedNames[$newPath] = 0;
            }

            $zip->addFile($filepath, $newPath);
            $addedAny = true;
        }

        $zip->close();

        if (! $addedAny || ! file_exists($zipPath)) {
            return $this->errorResponse('No se encontraron archivos válidos para comprimir.');
        }

        return response()->download($zipPath, $receiptUnit->id . '.zip')
            ->deleteFileAfterSend(true);
    }

    public function downloadticket($id)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->findOrFail($id);
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('folionumber', $receiptunit->id)->first();
        $trace = StorageUnitTrace::controlAccessUser()->where('receiptunitid', $id)->first();
        $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Bridgestone.ticket';
        $pdf = PDF::loadView($fortmat, compact('receiptunit', 'trace', 'orderDelivery'));

        return $pdf->download('Ticket de peso -' . $receiptunit->id . '.pdf');
    }
}
