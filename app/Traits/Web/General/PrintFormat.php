<?php

namespace App\Traits\Web\General;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\General\StorageComment;
use App\Models\Yard\GenerateAccessWarehouse;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageFolioClient;
use App\Traits\ResponseApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

trait PrintFormat
{
    use ResponseApi;

    public function printFormatInvoice($receiptunit): JsonResponse
    {
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
            })->where('s.receiptunitid', $receiptunit->id)->get();

        $comment = StorageComment::where('modelid', $receiptunit->id)->where('modeltype', 'ReceiptUnits')->first();
        $logo = SystemWarehouse::where('warehouseid', $receiptunit->warehouseid)->select('auxiliary09')->first();
        $firma = ReceiptUnitKey::controlAccessUser()->where('folionumber', $receiptunit->id)->first();

        // Henkel One Roof
        if ($receiptunit->centerid === 'HENKELTOL01') {
            $folio = StorageFolioClient::where('folionumber', $receiptunit->id)->value('folioclient');
            if ($receiptunit->razonstatusid === '1') {
                $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Henkel.InvoiceHenkel';
            } else {
                $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Henkel.InvoiceHenkelReceipt';
            }
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified', 'folio'));

            return $this->printFormat($receiptunit, $pdf);
        }

        //  Planta Henkel
        if ($receiptunit->centerid === 'PLANHENKTOL') {
            $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Henkel.InvoicePlantHenkel';
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified'));

            return $this->printFormat($receiptunit, $pdf);
        }

        //	Kraft
        if ($receiptunit->centerid === 'AVIOR-INT') {
            if ($receiptunit->unittype->auxiliary01 === 'NAC') {
                $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Kraft.Nacional';
            }
            if ($receiptunit->unittype->auxiliary01 === 'EXP') {
                $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Kraft.Exportacion';
            }
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified'));

            return $this->printFormat($receiptunit, $pdf);
        }

        // Barry
        if ($receiptunit->centerid == 'BARRYTOL') {
            $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.Barry.InvoiceBarry';
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified', 'comment'));

            return $this->printFormat($receiptunit, $pdf);

        }

        //  Impresiones Estandar
        if ($receiptunit->center->formatinvoice === 'Estandar') {
            $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.General.Estandar';
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified', 'logo', 'comment', 'firma'));
        } else {
            $fortmat = 'Yards.ReceiptUnits.InvoiceFormats.General.Invoice';
            $pdf = PDF::loadView($fortmat, compact('receiptunit', 'verified', 'logo', 'comment', 'firma'));
            $pdf->setPaper('A4', 'landscape');
        }

        return $this->printFormat($receiptunit, $pdf);
    }

    private function printFormat($receiptunit, $pdf): JsonResponse
    {
        $path = $this->createDirectoryPath($receiptunit->centerid);
        $fileName = 'Folio-' . $receiptunit->id . '-' . Str::random(10) . '.pdf';
        $pdfContent = $pdf->output();
        Storage::put($path . '/' . $fileName, $pdfContent);
        $fileFolio = base64_encode(Storage::get($path . '/' . $fileName));

        return $this->getResponseSuccess('generatePDFToFolio', ['pdf' => $fileFolio]);
    }

    private function createDirectoryPath($centerId): string
    {
        $directory = 'public/' . $centerId . '/Formats';
        if (! Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0775, true);
        }

        return $directory;
    }

    //    Genera el formato para el QR de  planta en el modulo de circuito
    public function printFormatAccess($id): Response
    {
        $data = GenerateAccessWarehouse::controlAccessUser()->where('storageaccesswarehouse.id', $id)
            ->leftJoin('systemcenterdistributions as cd', 'storageaccesswarehouse.centerid', '=', 'cd.centerid')
            ->leftJoin('systemwarehouses as sw', 'storageaccesswarehouse.warehouseid', '=', 'sw.warehouseid')
            ->leftJoin('storageoperators as so', 'storageaccesswarehouse.operatorid', '=', 'so.id')
            ->leftJoin('storagelabeltrucks as sl', 'storageaccesswarehouse.boxid', '=', 'sl.id')
            ->leftJoin('storagelabeltrucks as st', 'storageaccesswarehouse.truckid', '=', 'st.id')
            ->leftJoin('systemstatus as se', 'storageaccesswarehouse.statusid', '=', 'se.statusid')
            ->leftJoin('shipmentcarriers as sc', 'sl.carrierid', '=', 'sc.id')
            ->select([
                'cd.name as center',
                'sw.name as warehouse',
                'so.name as operador',
                'so.drivernumber',
                'so.phone',
                'so.barcode as barcodeoperator',
                'sc.name as carrier',
                'sl.boxnumber as caja',
                'sl.barcode as barcodebox',
                'st.boxnumber as tracto',
                'st.barcode as barcodetruck',
                'storageaccesswarehouse.datestart',
                'storageaccesswarehouse.datefinish',
                'storageaccesswarehouse.id',
                'storageaccesswarehouse.statusid',
                'storageaccesswarehouse.shipmentid',
                'storageaccesswarehouse.confirmationnumber',
                'storageaccesswarehouse.auxiliary01 as token',
                'se.name as status',
                'sc.auxiliary04 as qr',
                'sc.auxiliary05 as logo',
            ])->first();
        $fortmat = 'Yards.GenerateAccess.partials.format';
        $pdf = PDF::loadView($fortmat, compact('data'));

        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }
}
