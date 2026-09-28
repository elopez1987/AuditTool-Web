<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportRequestZareInverse implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            \Log::info($row);
            $order = OrderDeliverie::controlAccessUser()
                ->where('shipmentid', trim($row['shipmentid']))->first();
            \Log::info($order);
            if (empty($order)) {
                StorageLogInverse::createNewLog('No existe número de embarque ingresado', $row['shipmentid']);

                continue;
            }
            $incidence = StorageIncidenceJourney::controlAccessUser()->where('orderid', $order->id)
                ->select(['centerid', 'warehouseid', 'orderid', 'folioid'])->first();
            \Log::info($incidence);
            if (empty($incidence)) {
                StorageLogInverse::createNewLog('No existe incidencia para el número de embarque ingresado', $row['shipmentid']);

                continue;
            }
            $inverse = StorageScheduleInverse::where('orderid', $order->id)->first();
            if (empty($inverse)) {
                StorageLogInverse::createNewLog('No número de embarque no existe en la bitácora de devoluciones', $row['shipmentid']);

                continue;
            }
            \Log::info('Inverse:' . $inverse);
            $zare = StorageScheduleInverseTraffic::where('centerid', $incidence->centerid)
                ->where('warehouseid', $incidence->warehouseid)
                ->where('orderid', $incidence->orderid)
                ->where('inverseid', $inverse->id)
                ->where('delivery', trim($row['delivery']))
                ->update([
                    'typereport' => trim((string) $row['type']),
                    'requestzare' => Carbon::now(),
                    'username' => Auth::user()->username,
                ]);

            \Log::info('Zare:' . $zare);

            $delivery = StorageScheduleInverseTraffic::where('delivery', '=', trim($row['delivery']))
                ->where('partnumberid', trim($row['partnumberid']))->first();
            if (! empty($delivery)) {
                \Log::info('Delivery:' . $delivery);
                $delivery->zare = trim((string) $row['zare']);
                $delivery->responsezare = Carbon::now();
                $delivery->save();
            }

            StorageScheduleInverseTraffic::where('centerid', $incidence->centerid)
                ->where('warehouseid', $incidence->warehouseid)
                ->where('orderid', $incidence->orderid)
                ->where('inverseid', $inverse->id)
                ->where('delivery', trim($row['delivery']))
                ->where('zare', trim($row['zare']))
                ->update([
                    'requestne' => Carbon::now(),
                    'ne' => trim((string) $row['ne']),
                    'adjust' => trim((string) $row['adjust']),
                    'responsene' => Carbon::now(),
                    'requestnc' => Carbon::now(),
                ]);

            $itemNC = StorageScheduleInverseTraffic::where('delivery', trim($row['delivery']))
                ->where('zare', trim($row['zare']))
                ->where('ne', trim($row['ne']))->first();
            if (! empty($itemNC)) {
                \Log::info('NC:' . $itemNC);
                $itemNC->nc = trim((string) $row['nc']);
                $itemNC->responsenc = Carbon::now();
                $itemNC->save();
            }

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
