<?php

namespace App\Http\Controllers\Configuration\WebService;

use App\Exports\Configuration\WebService\ExportLogWebService;
use App\Http\Controllers\BaseController;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Izel\RegisterDataToIzel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class WebServiceLogController extends BaseController
{

    use RegisterDataToIzel;

    public function index()
    {
        $this->authorize('webservicelog');
        return view('Configuration.LogWebService.index');
    }

    public function getDataLogsWs(Request $request)
    {
        $data = SystemEventWebServiceIzel::controlAccessUser()->with('center','warehouse')
            ->FolioNumber($request->get('folionumber'))
            ->BoxNumber($request->get('boxnumber'))->orderBy('id','desc')->limit(200)->get();
        return DataTables::of($data)
            ->addColumn('btn', 'Configuration.LogWebService.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }


//    Reprocesa la interface desde la vista de logs
    public function reprocessizel($id)
    {
        $log = SystemEventWebServiceIzel::findOrFail($id);
        $receiptunit = ReceiptUnit::where('id', $log->folionumber)->first();
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        $type = $this->getType($log);
        $this->processIntefaceToIzel($receiptunit, $parameter, $type);
        return redirect('/webservicelogs')->with(session()->flash('info', 'Registro agregado con exito'));
    }


//    Reprocesar la interface de Recurso confiable
    public function reporcesswebservicercontrol($id)
    {
        $register = SystemEventWebServiceIzel::findOrFail($id);
        $shipment = OrderDeliverie::where('shipmentid', $register->folionumber)->first();
        if (!empty($shipment)) {
            EdiShipment::createEDIForPlanToShipment($shipment);
            return $this->redirectBackSuccess();
        } else {
            return $this->errorResponse('No existe registro de numero de embarque en el plan :' . $register->folionumber);
        }
    }


    /**
     * @param $log
     * @return string
     */
    private function getType($log): string
    {
        if ($log->servicename == 'receiptunitadd') {
            $type = 'IN';
        } elseif ($log->servicename == 'receiptunitout') {
            $type = 'OUT';
        }
        return $type;
    }




    public function exportlogwebservice()
    {
        return Excel::download(new ExportLogWebService(), 'Reporte de Web Service.xlsx')->deleteFileAfterSend();
    }


}
