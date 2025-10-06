<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportRequestNEInverse implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $zare = StorageScheduleInverseTraffic::where('zare', trim($row['zare']))->first();
            if (empty($zare)) {
                StorageLogInverse::createNewLog('No existe número de Zare en la bitacora de devoluciones', $row['zare']);

                continue;
            }
            $zare->requestne = Carbon::now();
            $zare->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.zare' => 'required|numeric|digits_between:1,20',
        ];
    }
}
