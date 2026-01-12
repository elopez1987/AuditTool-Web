<?php

namespace App\Http\Controllers\Receipt\SchedulleV2;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\StorageSchedulleReceipt;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\StorageReason;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DownloadScheduleController extends BaseController
{
    public function index()
    {
        $status = $this->getStatusForSchema('SRE');

        return view('Receipt.SchedulleV2.index', compact('status'));
    }

    public function getDataSchedulleV2(Request $request)
    {
        $data = StorageSchedulleReceipt::controlAccessUser()
            ->Workday($request->get('workday'))
            ->Shipment($request->get('shipmentid'))
            ->select([
                'storageschedullereceipt.centerid',
                'storageschedullereceipt.warehouseid',
                'storageschedullereceipt.statusid',
                'storageschedullereceipt.value01',
                'sc.description as centername',
                'sw.name as warehousename',
                'storageschedullereceipt.id',
                'storageschedullereceipt.periodid',
                'storageschedullereceipt.workday',
                's1.name as secuencia',
                'ra.shipmentid',
                'r2.name as proveedor',
                's3.name as carriername',
                'ra.oc',
                'ra.billoflading as factura',
                'ra.auxiliary02 as po',
                'ra.partnumberid as material',
                'ra.quantity',
                'ra.downloadpriority as priority',
                'ra.comment',
                'i.description as tipo',
                's2.name as status',
                's2.auxiliary01 as color',
                'sl1.name as cortina',
                'ra.id as receiptid',
                'ra.downloadpriority',
                'ra.goodissuedate as goodissuedate',
                //                'ra.statusid',
                'ra.po as grsap',
            ])
            ->leftJoin('receiptasn as ra', function ($join) {
                $join->on('storageschedullereceipt.receiptid', '=', 'ra.id')
                    ->on('storageschedullereceipt.centerid', '=', 'ra.centerid')
                    ->on('storageschedullereceipt.warehouseid', '=', 'ra.warehouseid');
            })
            ->leftJoin('receiptproviders as r2', function ($join) {
                $join->on('ra.providerid', '=', 'r2.id')
                    ->on('ra.centerid', '=', 'r2.centerid')
                    ->on('ra.warehouseid', '=', 'r2.warehouseid');
            })
            ->leftJoin('shipmentcarriers as s3', function ($join) {
                $join->on('ra.carrierid', '=', 's3.id')
                    ->on('ra.centerid', '=', 's3.centerid');
            })
            ->leftJoin('receiptunits as ru', function ($join) {
                $join->on('ra.folionumber', '=', 'ru.id')
                    ->on('ra.centerid', '=', 'ru.centerid')
                    ->on('ra.warehouseid', '=', 'ru.warehouseid');
            })->leftJoin('storagelocations as sl1', function ($join) {
                $join->on('ru.locationid', '=', 'sl1.id')
                    ->on('ru.centerid', '=', 'sl1.centerid');
            })
            ->leftJoin('inventorystockcategories as i', 'ra.stockcategoryid', '=', 'i.id')
            ->leftJoin('systemstatus as s1', 'storageschedullereceipt.secuence', '=', 's1.statusid')
            ->leftJoin('systemstatus as s2', 'ra.statusid', '=', 's2.statusid')
            ->leftJoin('systemcenterdistributions as sc', 'storageschedullereceipt.centerid', '=', 'sc.centerid')
            ->leftJoin('systemwarehouses as sw', 'storageschedullereceipt.warehouseid', '=', 'sw.warehouseid')
            ->get();

        // Obtener las variables necesarias para el modal
        $providers = $this->getReceiptProviderForSelect();
        $carriers = $this->getCarriersForSelect();
        $categorystocks = InventoryStockCategory::controlAccessUser()->select('description', 'id')->get();
        $reasons = StorageReason::whereType('RE')->select('name', 'id')->get();

        return DataTables::of($data)
            ->addColumn('btn', function ($row) use ($providers, $carriers, $categorystocks, $reasons) {
                return view('Receipt.SchedulleV2.partials.buttons', [
                    'id' => $row->id,
                    'shipmentid' => $row->shipmentid,
                    'statusid' => $row->statusid,
                    'receiptid' => $row->receiptid,
                    'value01' => $row->value01,
                    'providers' => $providers,
                    'carriers' => $carriers,
                    'categorystocks' => $categorystocks,
                    'reasons' => $reasons,
                ])->render();
            })
            ->addColumn('prioridad', 'Receipt.SchedulleV2.addpriority')
            ->addColumn('po', 'Receipt.SchedulleV2.goodissuedate')
            ->rawColumns(['btn', 'prioridad', 'po'])
            ->toJson();
    }

    //	Esta funcion edita el registro el recibo en el plan de entrega
    public function addShipmentToScheduleedit(Request $request, $id)
    {
        $this->authorize('receiptasn.create');
        DB::beginTransaction();
        try {
            $schedulleReceipt = StorageSchedulleReceipt::controlAccessUser()->findOrFail($id);
            $hour = SystemStatus::where('statusid', $schedulleReceipt->secuence)->first();
            $combinedDateTime = $schedulleReceipt->workday . ' ' . $hour->auxiliary04;

            $formattedDateTime = Carbon::createFromFormat('Y-m-d H:i', $combinedDateTime);

            // Validar que la fecha y hora seleccionada no sean menores a la fecha y hora actual
            if ($formattedDateTime->lt(Carbon::now())) {
                DB::rollBack();

                // Respuesta para peticiones AJAX / JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede insertar un embarque menor a la fecha y hora actual',
                    ], 422);
                }

                // En caso de petición normal
                return back()->withErrors(['addasndate' => 'No se puede insertar un embarque menor a la fecha y hora actual']);
            }

            $parameterWarehouse = SystemWarehouse::whereWarehouseid($schedulleReceipt->warehouseid)->value('configurationflag');
            if (! empty($parameterWarehouse) && in_array('SWH-16', (array) $parameterWarehouse)) {
                $shipment = $this->getFolioReceipt($schedulleReceipt->centerid);
            } else {
                $shipment = $request['shipmentid'];
            }

            $defaultCarrier = ShipmentCarrier::controlAccessUser()->whereRaw("charindex('SCR-04', configurationflag) > 0")->first();

            $receipt = ReceiptAsn::create([
                'centerid' => $schedulleReceipt->centerid,
                'warehouseid' => $schedulleReceipt->warehouseid,
                'razonstatusid' => $request->razonstatusid,
                'stockcategoryid' => $request->stockcategoryid,
                'providerid' => $request->providerid,
                'shipmentid' => $shipment,
                'billoflading' => $request->billoflading,
                'partnumberid' => $request->partnumberid,
                'quantity' => $request->quantity,
                'carrierid' => $defaultCarrier->id,
                'oc' => $request->oc,
                'statusid' => 'PG',
                'addasndate' => $formattedDateTime,
                'workday' => Carbon::parse($schedulleReceipt->workday)->format('Y-m-d'),
                'auxiliary01' => Auth::user()->name, // Planeador del recibo
            ]);

            $schedulleReceipt->receiptid = $receipt->id;
            $schedulleReceipt->statusid = 'DPR-02';
            $schedulleReceipt->save();

            DB::commit();

            // Respuesta para peticiones AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Recibo agregado al plan de entrega',
                    'receipt_id' => $receipt->id,
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();

            // Respuesta para peticiones AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al agregar recibo, por favor intente nuevamente o informe al administrador.',
                ], 500);
            }

        }
    }
}
