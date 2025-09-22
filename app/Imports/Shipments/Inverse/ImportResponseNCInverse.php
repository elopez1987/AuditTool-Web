<?php

namespace App\Imports\Shipments\Inverse;

use App\Models\Shipments\StorageLogInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportResponseNCInverse implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $item = StorageScheduleInverseTraffic::where('delivery', trim($row['delivery']))
                ->where('zare', trim($row['zare']))
                ->where('ne', trim($row['ne']))->first();
            if (empty($item)) {
                StorageLogInverse::createNewLog('No existen registros con los datos ingresados (Delivery/Zare/NE)', $row['delivery']);
                continue;
            }
            $item->nc = trim($row['nc']);
            $item->responsenc = Carbon::now();
            $item->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.delivery' => 'required|numeric|digits_between:1,20',
            '*.zare' => 'required|numeric|digits_between:1,20',
            '*.ne' => 'required|numeric|digits_between:1,20',
            '*.nc' => 'required|numeric|digits_between:1,20',
        ];
    }
}
