<?php

namespace App\Http\Requests\Auth;

use App\Services\Security\SecurityValidationService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetTokenRequest extends FormRequest
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
            'Usuario' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/', // Solo caracteres alfanuméricos y algunos especiales
            ],
            'Password' => [
                'required',
                'string',
                'min:6',
                'max:100',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'Usuario.required' => 'El campo Usuario es obligatorio.',
            'Usuario.string' => 'El Usuario debe ser una cadena de texto.',
            'Usuario.min' => 'El Usuario debe tener al menos 3 caracteres.',
            'Usuario.max' => 'El Usuario no puede tener más de 50 caracteres.',
            'Usuario.regex' => 'El Usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',

            'Password.required' => 'El campo Password es obligatorio.',
            'Password.string' => 'El Password debe ser una cadena de texto.',
            'Password.min' => 'El Password debe tener al menos 6 caracteres.',
            'Password.max' => 'El Password no puede tener más de 100 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'Usuario' => 'usuario',
            'Password' => 'contraseña',
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
                'ServiceName' => 'getToken',
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
                    'ServiceName' => 'getToken',
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
            'Usuario' => trim($this->Usuario ?? ''),
            'Password' => $this->Password ?? '',
        ]);
    }

    /**
     * Get the sanitized and validated data
     */
    public function getSanitizedData(): array
    {
        return [
            'Usuario' => $this->validated()['Usuario'],
            'Password' => $this->validated()['Password'],
        ];
    }
}
