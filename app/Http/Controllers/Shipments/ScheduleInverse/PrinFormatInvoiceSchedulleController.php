<?php

namespace App\Http\Controllers\Shipments\ScheduleInverse;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ScheduleInverse\ScheduleInverseDetail;
use App\Models\Shipments\ScheduleInverse\StorageShipmentSchedule;
use Barryvdh\DomPDF\Facade\Pdf;

class PrinFormatInvoiceSchedulleController extends BaseController
{
    public function schedulleinverseprinformat($id)
    {
        $reserveschedule = StorageShipmentSchedule::controlAccessUser()->findOrFail($id);
        $details = ScheduleInverseDetail::controlAccessUser()->where('folioid', $id)
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'scheduleinversedetail.secuenceid')
            ->select([
                'scheduleinversedetail.folioid',
                'scheduleinversedetail.workday',
                'scheduleinversedetail.quantity',
                'systemstatus.name as bloque',
            ])->first();
        $shipments = StorageShipmentSchedule::controlAccessUser()->select([
            'vw_shipmentforschedulleinverse.shipmentid',
            'vw_shipmentforschedulleinverse.carriername',
            'vw_shipmentforschedulleinverse.customername',
            'vw_shipmentforschedulleinverse.quantity',
        ])
            ->leftJoin('storageshipmentsscheduledetail', function ($join) {
                $join->on('storageshipmentsschedule.id', '=', 'storageshipmentsscheduledetail.shipmentschedulleid')
                    ->on('storageshipmentsschedule.centerid', '=', 'storageshipmentsscheduledetail.centerid');
            })
            ->leftJoin('vw_shipmentforschedulleinverse', function ($join) {
                $join->on('storageshipmentsscheduledetail.incidencejourneyid', '=', 'vw_shipmentforschedulleinverse.folioid')
                    ->on('storageshipmentsscheduledetail.centerid', '=', 'vw_shipmentforschedulleinverse.centerid');
            })
            ->where('storageshipmentsschedule.id', $id)->get();
        $pdf = Pdf::loadView(
            'Shipments.ReserveSchedulleInverse.partials.pdf',
            compact('reserveschedule', 'details', 'shipments')
        );
        return $pdf->download('Folio_De_Devolución-' . $reserveschedule->id . '.pdf');
    }
}
