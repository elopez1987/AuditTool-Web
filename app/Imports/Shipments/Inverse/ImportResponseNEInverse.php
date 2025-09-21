<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportResponseNEInverse implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $zare = StorageScheduleInverseTraffic::where('zare', '=', trim($row['zare']))
                ->where('delivery', '=', trim($row['delivery']))->first();
            if (empty($zare)) {
                StorageLogInverse::createNewLog('No existen registros con las condiciones (Delivery/Zare) ingresadas', $row['zare']);
                continue;
            }
            $zare->ne = trim($row['ne']);
            $zare->adjust = trim($row['adjust']);
            $zare->responsene = Carbon::now();
            $zare->save();

            $operation = StorageScheduleInverseOperation::where('orderid', '=', $zare->orderid)->first();
            if (empty($operation->wmsdate)) {
                $operation->statusid = 'PZ';
                $operation->save();
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.zare' => 'required|numeric|digits_between:1,20',
            '*.delivery' => 'required|numeric|digits_between:1,20',
            '*.ne' => 'required|numeric|digits_between:1,20',
            '*.adjust' => 'required|string|in:S,N',
        ];
    }
}
