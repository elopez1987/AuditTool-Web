<?php

namespace App\Http\Requests\Auth;

use App\Services\Security\SecurityValidationService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/', // Solo caracteres alfanuméricos y algunos especiales
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
            ],
            'serialnumber' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9_-]+$/', // Solo caracteres alfanuméricos, guiones y guiones bajos
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'El campo username es obligatorio.',
            'username.string' => 'El username debe ser una cadena de texto.',
            'username.min' => 'El username debe tener al menos 3 caracteres.',
            'username.max' => 'El username no puede tener más de 50 caracteres.',
            'username.regex' => 'El username solo puede contener letras, números, puntos, guiones y guiones bajos.',

            'password.required' => 'El campo password es obligatorio.',
            'password.string' => 'El password debe ser una cadena de texto.',
            'password.min' => 'El password debe tener al menos 6 caracteres.',
            'password.max' => 'El password no puede tener más de 100 caracteres.',

            'serialnumber.string' => 'El número de serie debe ser una cadena de texto.',
            'serialnumber.max' => 'El número de serie no puede tener más de 100 caracteres.',
            'serialnumber.regex' => 'El número de serie solo puede contener letras, números, guiones y guiones bajos.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'username' => 'nombre de usuario',
            'password' => 'contraseña',
            'serialnumber' => 'número de serie',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $message = implode(' ', $errors);

        throw new HttpResponseException(
            response()->json([
                'ServiceName' => 'loginApp',
                'ProcessDate' => now()->format('Y-m-d H:i:s'),
                'Code' => '1',
                'Status' => 'ERROR',
                'Message' => $message,
            ], 422)
        );
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Validar amenazas de seguridad
        $securityService = app(SecurityValidationService::class);
        $threats = $securityService->validateRequest($this);

        // Bloquear solicitud si hay amenazas de alta severidad
        if ($securityService->shouldBlockRequest($threats)) {
            $summary = $securityService->getThreatSummary($threats);

            // Agregar IP a blacklist temporal
            $securityService->addToBlacklist($this->ip(), 30);

            throw new HttpResponseException(
                response()->json([
                    'ServiceName' => 'loginApp',
                    'ProcessDate' => now()->format('Y-m-d H:i:s'),
                    'Code' => '-3',
                    'Status' => 'SECURITY_BLOCKED',
                    'Message' => 'Solicitud bloqueada por razones de seguridad.',
                    'Data' => [
                        'threat_summary' => $summary,
                    ],
                ], 403)
            );
        }

        // Sanitizar y limpiar los datos de entrada
        $this->merge([
            'username' => trim($this->username ?? ''),
            'password' => $this->password ?? '',
            'serialnumber' => trim($this->serialnumber ?? ''),
        ]);
    }

    /**
     * Get the sanitized and validated data
     */
    public function getSanitizedData(): array
    {
        $validated = $this->validated();

        return [
            'username' => $validated['username'],
            'password' => $validated['password'],
            'serialnumber' => $validated['serialnumber'] ?? null,
        ];
    }

    /**
     * Get sanitized data for logging (without passwords)
     */
    public function getLoggableData(): array
    {
        $validated = $this->validated();

        return [
            'username' => $validated['username'],
            'password' => '***REDACTED***',
            'serialnumber' => $validated['serialnumber'] ?? null,
        ];
    }
}
