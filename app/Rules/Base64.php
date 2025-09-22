<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64 implements Rule
{
    public function passes($attribute, $value)
    {
        // Verifica si la cadena es válida en Base64
        return base64_encode(base64_decode($value, true)) === $value;
    }

    // Mensaje de error
    public function message()
    {
        return 'El :attribute debe estar en formato Base64 válido.';
    }
}
