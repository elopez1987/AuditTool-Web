<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportRequestZareInverse implements ToCollection, WithHeadingRow, WithValidation
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $order = OrderDeliverie::controlAccessUser()
                ->where('shipmentid', trim($row['shipmentid']))->first();
            if (empty($order)) {
                StorageLogInverse::createNewLog('No existe número de embarque ingresado', $row['shipmentid']);
                continue;
            }
            $incidence = StorageIncidenceJourney::controlAccessUser()->where('orderid', $order->id)
                ->select(['centerid', 'warehouseid', 'orderid', 'folioid'])->first();
            if (empty($incidence)) {
                StorageLogInverse::createNewLog('No existe incidencia para elnúmero de embarque ingresado', $row['shipmentid']);
                continue;
            }
            $inverse = StorageScheduleInverse::where('orderid', $order->id)->first();
            if (empty($inverse)) {
                StorageLogInverse::createNewLog('No número de embarque no existe en la bitacora de devoluciones', $row['shipmentid']);
                continue;
            }
            StorageScheduleInverseTraffic::where('centerid', $incidence->centerid)
                ->where('warehouseid', $incidence->warehouseid)
                ->where('orderid', $incidence->orderid)
                ->where('inverseid', $inverse->id)
                ->where('delivery', trim($row['delivery']))->update([
                    'typereport' => trim($row['type']),
                    'requestzare' => Carbon::now(),
                    'username' => \Auth::user()->username
                ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.shipmentid' => 'required',
            '*.type' => 'required|string|max:3|in:FIS,VIR',
            '*.delivery' => 'required|numeric|digits_between:1,25',
        ];
    }


}
