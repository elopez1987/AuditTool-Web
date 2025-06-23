<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Evidence\EvidenceContainer;
use Illuminate\Http\Request;

class ReportEvidenceController extends BaseController
{


    public function index()
    {
        return view('Evidence.ReportEvidence.index');
    }

    public function getKardexContainer(Request $request)
    {
        if ($request->ajax() && $request['lpnid'] != '' || $request['partnumberid'] != '' || $request['shipmentid'] != '' || $request['workday'] != '') {
            $data = EvidenceContainer::controlAccessUser()
                ->leftJoin('storageevidenceoperations', function ($join) {
                    $join->on('storageevidencecontainers.shipmentid', '=', 'storageevidenceoperations.recordid')
                        ->on('storageevidencecontainers.centerid', '=', 'storageevidenceoperations.centerid');
                })
                ->leftJoin('inventorypartnumbers', function ($join) {
                    $join->on('storageevidencecontainers.partnumberid', '=', 'inventorypartnumbers.id')
                        ->on('storageevidencecontainers.centerid', '=', 'inventorypartnumbers.centerid');
                })
                ->leftJoin('ordercustomers', function ($join) {
                    $join->on('storageevidenceoperations.customerid', '=', 'ordercustomers.id')
                        ->on('storageevidenceoperations.centerid', '=', 'ordercustomers.centerid');
                })
                ->leftJoin('users as u1', function ($join) {
                    $join->on('storageevidencecontainers.scanusercheck', '=', 'u1.username');
                })
                ->select([
                    'storageevidenceoperations.shipmentid',
                    'storageevidenceoperations.workday',
                    'ordercustomers.name as customer',
                    'ordercustomers.city',
                    'inventorypartnumbers.prtnum as partnumberid',
                    'inventorypartnumbers.lngdsc as description',
                    'storageevidencecontainers.quantity',
                    'storageevidencecontainers.lpnid',
                    'storageevidencecontainers.adddatecheck',
                    'u1.name as scanusercheck'
                ])
                ->ForWorkday($request->get('workday'))
                ->ForLpn($request->get('lpnid'))
                ->ForShipment($request->get('shipmentid'))
                ->ForSku($request->get('partnumberid'))
                ->get();
        }
        return $this->showDataToView($data, '');

    }


}
