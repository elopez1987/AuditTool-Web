<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportResponseZareInverse implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $delivery = StorageScheduleInverseTraffic::where('delivery', '=', trim($row['delivery']))
                ->where('partnumberid', trim($row['partnumberid']))->first();
            if (empty($delivery)) {
                StorageLogInverse::createNewLog('No existe número de factura en la bitacora de devoluciones', trim($row['delivery']));

                continue;
            }
            $delivery->zare = trim($row['zare']);
            $delivery->responsezare = Carbon::now();
            $delivery->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.delivery' => 'required|numeric|digits_between:1,20',
            '*.zare' => 'required|numeric|digits_between:1,20',
            '*.partnumberid' => 'required|digits_between:1,20',
        ];
    }
}
