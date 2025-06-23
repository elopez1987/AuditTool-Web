<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

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
