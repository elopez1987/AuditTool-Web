<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class LocalLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Nombre de usuario de la cuenta local.
             *
             * @example operator
             */
            'username' => ['required', 'string', 'max:20'],
            /**
             * Contraseña de la cuenta local. No debe registrarse ni almacenarse en el cliente.
             */
            'password' => ['required', 'string', 'max:1024'],
            /**
             * Número de serie del dispositivo; omitirlo no impide iniciar sesión.
             *
             * @example DEVICE-001
             */
            'serialnumber' => ['nullable', 'string', 'max:30'],
        ];
    }
}
