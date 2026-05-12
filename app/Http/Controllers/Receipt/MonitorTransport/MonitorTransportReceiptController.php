<?php

namespace App\Http\Controllers\Receipt\MonitorTransport;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MonitorTransportReceiptController extends BaseController
{
    public function index()
    {
        $receipts = ReceiptAsn::with([
            'provider:id,name',
            'center:centerid,name,description',
            'warehouse:warehouseid,name',
        ])
            ->leftJoin('shipmenttransport', function ($join) {
                $join->on('receiptasn.id', '=', 'shipmenttransport.orderid')
                    ->where('shipmenttransport.auxiliary04', '=', 'ReceiptAsn');
            })
            ->where('statusid', 'PG')
            ->whereIn('providerid', function ($query) {
                $query->select('providerid')
                    ->from('securityaccessproviders')
                    ->where('username', auth()->user()->username);
            })
            ->select(['receiptasn.id', 'receiptasn.providerid', 'receiptasn.centerid',
                'receiptasn.shipmentid', 'receiptasn.carrierid',
                'receiptasn.warehouseid', 'receiptasn.statusid', 'receiptasn.auxiliary01',
                'receiptasn.addasndate', 'has_data', 'shipmenttransport.orderid'])
            ->get();

        return view('Receipt.MonitorTransport.index', compact('receipts'));
    }

    public function edit($id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $transport = StorageShipmentTransport::where('orderid', $id)
            ->where('auxiliary04', 'ReceiptAsn')
            ->first();
        $carriers = $this->getCarriersForSelect();

        return view('Receipt.MonitorTransport.edit',
            compact('receipt', 'carriers', 'transport'));
    }

    public function update(Request $request, $id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        StorageShipmentTransport::updateOrCreate(
            [
                'centerid' => $receipt->centerid,
                'warehouseid' => $receipt->warehouseid,
                'orderid' => $receipt->id,
                'auxiliary04' => 'ReceiptAsn',
            ],
            [
                'carrierid' => $request->input('carrierid'),
                'operatorname' => $request->input('operatorname'),
                'drivernumber' => $request->input('drivernumber'),
                'phone' => $request->input('phone'),
                'boxnumber' => $request->input('boxnumber'),
                'trucknumber' => $request->input('trucknumber'),
                'ecobox' => $request->input('ecobox'),
                'ecotruck' => $request->input('ecotruck'),
                'has_data' => '1',
            ],
        );

        return $this->redirectBackSuccessMessage('Datos del transporte agregado con exito.');
    }

    public function printFormatQr($id)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $data = ReceiptAsn::with('transport')->findOrFail($id);
        $fortmat = 'Receipt.MonitorTransport.partials.format';
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
        ])->loadView($fortmat, compact('data'));

        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }
}
