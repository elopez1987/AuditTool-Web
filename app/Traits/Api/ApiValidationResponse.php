<?php

namespace App\Traits\Request;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * Trait ApiValidationResponse
 *
 * Use this trait in API FormRequest classes to return validation errors
 * in the modern API response structure.
 */
trait ApiValidationResponse
{
    /**
     * Handle a failed validation attempt.
     *
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->buildModernValidationErrorResponse($validator)
        );
    }

    /**
     * Build modern validation error response
     */
    private function buildModernValidationErrorResponse(Validator $validator): JsonResponse
    {
        // Get the service name from the request route or use a default
        $serviceName = $this->getServiceName();

        return response()->json([
            'success' => false,
            'code' => 422,
            'message' => 'Errores de validación',
            'meta' => [
                'version' => config('api.version', '1.0.0'),
                'service' => $serviceName,
                'processDate' => Carbon::now()->utc()->toISOString(),
                'errorCode' => 'VALIDATION_ERROR',
                'errors' => $validator->errors()->toArray(),
            ],
            'data' => null,
        ], 422);
    }

    /**
     * Get the service name from the route or return a default
     */
    private function getServiceName(): string
    {
        // Try to get from route name
        $routeName = $this->route()?->getName();
        if ($routeName) {
            // Convert route name to service name (e.g., 'api.warehouses.index' -> 'warehouses')
            $parts = explode('.', $routeName);
            if (count($parts) >= 2) {
                return $parts[1];
            }
        }

        // Try to extract from URI
        $uri = $this->path();
        $uriParts = explode('/', trim($uri, '/'));
        if (count($uriParts) >= 2) {
            return $uriParts[count($uriParts) - 1]; // Last segment
        }

        return 'api-service';
    }

    /**
     * Get custom messages for validation errors (optional override)
     */
    public function messages(): array
    {
        return [
            // Override in child classes if needed
        ];
    }

    /**
     * Get custom attributes for validator errors (optional override)
     */
    public function attributes(): array
    {
        return [
            // Override in child classes if needed
        ];
    }
}
