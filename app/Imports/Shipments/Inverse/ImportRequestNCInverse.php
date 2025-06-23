<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportRequestNCInverse implements ToCollection, WithHeadingRow, WithValidation
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $item = StorageScheduleInverseTraffic::where('ne', trim($row['ne']))->first();
            if (empty($item)) {
                StorageLogInverse::createNewLog('No existen registros con la Nota de Entrega ingresada', $row['ne']);
                continue;
            }
            $item->requestnc = Carbon::now();
            $item->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.ne' => 'required|numeric|digits_between:1,20'
        ];
    }

    
}
