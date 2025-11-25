<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderdeliveryTrace;
use Auth;
use Illuminate\Support\Facades\DB;
use Log;

class ReturnStartGoodIssueController extends BaseController
{
    // FO  -> Estatus cargado
    public function index($id)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()
                ->with(['header', 'operation'])
                ->where('id', $id)->first();

            $statusold = $orderDelivery->statusid;

            $orderDelivery->statusid = 'FO';
            $orderDelivery->save();

            $orderDelivery->header->statusid = 'FO';
            $orderDelivery->header->startgoodissuedate = null;
            $orderDelivery->header->save();

            $orderDelivery->operation->senddocument = null;
            $orderDelivery->operation->save();

            StorageComment::create([
                'modeltype' => 'orderdeliveries',
                'modelid' => $orderDelivery->id,
                'comment' => 'Envío de documentos a operaciones.',
                'createuser' => Auth::user()->id,
            ]);

            OrderdeliveryTrace::saveEventShipment($orderDelivery, 'EEM-14', $statusold);

            DB::commit();

            return $this->redirectBackSuccessMessage('Se ha retornado el pedido al monitor de operaciones.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse($e->getMessage());
        }
    }
}
