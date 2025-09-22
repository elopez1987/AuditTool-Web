<?php

namespace App\Rules;

use DB;
use Illuminate\Contracts\Validation\Rule;

class PartnumberOrBarcodeExists implements Rule
{
    public function passes($attribute, $value)
    {
        return DB::table('inventorypartnumbers')
            ->where('prtnum', $value)
            ->orWhere('barcode', $value)
            ->exists();
    }

    public function message()
    {
        return 'El :attribute no existe en inventorypartnumbers (prtnum o barcode).';
    }
}
