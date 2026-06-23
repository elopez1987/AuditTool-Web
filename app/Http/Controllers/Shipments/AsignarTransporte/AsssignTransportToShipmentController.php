<?php

namespace App\Http\Controllers\Shipments\AsignarTransporte;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class AsssignTransportToShipmentController extends BaseController
{
    public function index()
    {
        return view('Shipments.AsignarTransporte.index');
    }

    public function getShipmentsForUnassignedTransport()
    {
        $carriers = $this->getCarrierForUser();

        $data = OrderDeliverie::controlAccessUser()
            ->with([
                'carrier:id,name',       // relación con shipmentcarriers
                'customer:id,name,city', // relación con ordercustomers
                'transport',              // relación con shipmenttransport
            ])
            ->whereNotIn('statusid', ['CA', 'DH'])
            ->whereNull('folionumber')
            ->whereHas('transport', function ($query) {
                $query->whereNull('has_data')
                    ->whereNull('has_registered');
            })
            ->CarrierIn($carriers)
            ->get([
                'centerid',
                'warehouseid',
                'id',
                'shipmentid',
                'carrierid',
                'customerid',
                'folionumber',
                'loadingdate',
                'downloaddate',
            ]);

        return $this->showDataToView($data, 'Shipments.AsignarTransporte.partials.buttons');
    }

    public function edit($id)
    {
        $order = OrderDeliverie::controlAccessUser()
            ->with(['customer:id,name,city', 'carrier:id,name'])
            ->findOrFail($id);
        $units = ReceiptUnit::controlAccessUser()
            ->where('carrierid', $order->carrierid)
            ->whereNotIn('statusid', ['TR'])
            ->where(function ($q) {
                $q->whereNull('shipmentid')         // NULL
                    ->orWhere('shipmentid', '')       // cadena vacía
                    ->orWhere('shipmentid', '0')      // string con 0
                    ->orWhere('shipmentid', '00')     // otras variantes
                    ->orWhere('shipmentid', '000')
                    ->orWhere('shipmentid', '0000');
            })
            ->get();

        return view('Shipments.AsignarTransporte.edit', compact('order', 'units'));
    }

    public function update(Request $request, $id)
    {

        if (! $request->filled('folionumber')) {
            return $this->errorResponse('El número de folio es requerido');
        }

        DB::beginTransaction();
        try {

            // Buscar la unidad
            $receiptunit = ReceiptUnit::searchForId($request->folionumber);
            if (! $receiptunit) {
                return $this->errorResponse('No se encontró la unidad de recepción');
            }

            // Buscar embarque
            $shipment = OrderDeliverie::controlAccessUser()->find($id);
            if (! $shipment) {
                return $this->errorResponse('No se encontró el embarque');
            }

            // Actualiza usando update() en lugar de save() para reducir consultas
            $shipment->update([
                'folionumber' => $receiptunit->id,
            ]);

            $receiptunit->update([
                'shipmentid' => $shipment->shipmentid,
            ]);

            // Transporte
            StorageShipmentTransport::controlAccessUser()
                ->where('orderid', $shipment->id)
                ->update([
                    'has_registered' => 1,
                    'has_data' => 1,
                ]);

            DB::commit();

            return $this->redirectBackSuccess();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al procesar la solicitud AsssignTransportToShipmentController', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Ocurrió un error al asignar el transporte');
        }

    }
}
