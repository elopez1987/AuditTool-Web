<?php

namespace App\Traits\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Random\RandomException;

//    'Code' => '0' => 'Respuesta exitosa',
//    'Code' => '-1' => 'Error en la respuesta'

// Códigos de éxito
// 200 OK → La solicitud se procesó correctamente.
// 201 Created → Recurso creado exitosamente.
// Errores del cliente (4xx)
// Indican que la solicitud tiene algún problema:
// 400 Bad Request → Error de sintaxis o parámetros inválidos.
// 401 Unauthorized → Falta la autenticación o el token es inválido.
// 403 Forbidden → No tienes permisos para esa acción.
// 404 Not Found → El recurso solicitado no existe.
// 405 Method Not Allowed → El método HTTP (GET, POST, etc.) no está permitido.
// 409 Conflict → Conflicto con el estado actual del recurso.
// 422 Unprocessable Entity → La solicitud es válida, pero no se pudo procesar (por ejemplo, datos mal formateados).
// 429 Too Many Requests → Has excedido el límite de peticiones (rate limit).
// Errores del servidor (5xx)
// Problemas del lado de la API:
// 500 Internal Server Error → Error genérico del servidor.
// 502 Bad Gateway → El servidor recibió una respuesta inválida de otro servicio.
// 503 Service Unavailable → El servicio no está disponible temporalmente (sobrecarga, mantenimiento).
// 504 Gateway Timeout → El servidor no respondió a tiempo.

trait ModernApiResponse
{
    /**
     * Return a success JSON response with modern structure.
     */
    protected function modernSuccessResponse(
        mixed $data,
        string $message = 'Operación exitosa',
        string $serviceName = 'api-service',
        int $httpStatus = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'code' => $httpStatus,
            'message' => $message,
            'meta' => [
                'version' => config('api.version', '1.0.0'),
                'service' => $serviceName,
                'processDate' => Carbon::now()->utc()->toISOString(),
            ],
            'data' => $data,
        ], $httpStatus);
    }

    /**
     * Return an error JSON response with modern structure.
     *
     * @param  int  $code
     */
    protected function modernErrorResponse(
        string $message,
        string $serviceName = 'api-service',
        int $httpStatus = 400,
        mixed $data = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'code' => $httpStatus,
            'message' => $message,
            'meta' => [
                'version' => config('api.version', '1.0.0'),
                'service' => $serviceName,
                'processDate' => Carbon::now()->toIso8601String(),
            ],
            'data' => $data,
        ], $httpStatus);
    }

    /**
     * Return a validation error response with modern structure.
     */
    protected function modernValidationErrorResponse(
        array $errors,
        string $message = 'Errores de validación',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'code' => 422,
            'message' => $message,
            'meta' => [
                'version' => config('api.version', '1.0.0'),
                'service' => $serviceName,
                'processDate' => Carbon::now()->toIso8601String(),
            ],
            'data' => [
                'errors' => $errors,
            ],
        ], 422);
    }

    /**
     * Return an unauthorized error response with modern structure.
     */
    protected function modernUnauthorizedResponse(
        string $message = 'No autorizado',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 401
        );
    }

    /**
     * Return a forbidden error response with modern structure.
     */
    protected function modernForbiddenResponse(
        string $message = 'Acceso denegado',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 403
        );
    }

    /**
     * Return a not found error response with modern structure.
     */
    protected function modernNotFoundResponse(
        string $message = 'Recurso no encontrado',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 404
        );
    }

    /**
     * Return a server error response with modern structure.
     */
    protected function modernServerErrorResponse(
        string $message = 'Error interno del servidor',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 500
        );
    }

    /**
     * Return a server created response with modern structure.
     */
    protected function modernCreatedResponse(
        string $message = 'Recurso creado exitosamente.',
        string $serviceName = 'api-service-created'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 201
        );
    }

    /**
     * Return a bad-request error response with modern structure. Use this
     * for malformed requests that are not covered by field validation
     * (e.g. an unparsable JSON body).
     */
    protected function modernBadRequestResponse(
        string $message = 'La solicitud no pudo ser interpretada.',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 400
        );
    }

    /**
     * Return a method-not-allowed error response with modern structure.
     *
     * @param  list<string>  $allowedMethods
     */
    protected function modernMethodNotAllowedResponse(
        array $allowedMethods = [],
        string $message = 'El método HTTP utilizado no está permitido para este recurso.',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 405,
            data: $allowedMethods ? ['allowedMethods' => $allowedMethods] : null,
        );
    }

    /**
     * Return a too-many-requests (rate limit) error response with modern
     * structure.
     */
    protected function modernTooManyRequestsResponse(
        ?int $retryAfter = null,
        string $message = 'Has excedido el límite de peticiones. Intenta de nuevo más tarde.',
        string $serviceName = 'api-service'
    ): JsonResponse {
        return $this->modernErrorResponse(
            message: $message,
            serviceName: $serviceName,
            httpStatus: 429,
            data: $retryAfter !== null ? ['retryAfterSeconds' => $retryAfter] : null,
        );
    }

    /**
     * Resolve a best-effort service name from the current request. Intended
     * for building error responses outside of a controller context (e.g.
     * from the global exception handler), where no explicit service name
     * is available.
     */
    protected function resolveServiceNameFromRequest(Request $request): string
    {
        $routeName = $request->route()?->getName();

        if ($routeName) {
            $segments = explode('.', $routeName);

            return end($segments) ?: 'api-service';
        }

        $uriSegments = array_values(array_filter(explode('/', trim($request->path(), '/'))));

        return $uriSegments ? end($uriSegments) : 'api-service';
    }

    /**
     * Build a structured data response for authentication.
     */
    protected function buildAuthData(
        object $user,
        string $token,
        int $expiresIn = 86400,
        ?string $refreshToken = null,
        array $additionalData = []
    ): array {
        $expiresAt = Carbon::now()->addSeconds($expiresIn);

        $data = [
            'user' => [
                'name' => $user->name ?? null,
                'centerId' => $user->access->centerid ?? null,
                'centerName' => $user->access->center->name ?? null,
            ],
            'auth' => [
                'accessToken' => $token,
                'tokenType' => 'Bearer',
                'expiresIn' => $expiresIn,
                'expiresAt' => $expiresAt->format('Y-m-d H:i:s'),
            ],
        ];

        // Add refresh token if provided
        if ($refreshToken) {
            $data['auth']['refreshToken'] = $refreshToken;
        }

        // Merge any additional data
        return array_merge($data, $additionalData);
    }

    /**
     * Generate a secure refresh token.
     *
     * @throws RandomException
     */
    protected function generateRefreshToken(int $length = 64): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Build paginated data structure.
     */
    protected function buildPaginatedData(
        mixed $items,
        int $total,
        int $currentPage,
        int $perPage,
        array $additionalData = []
    ): array {
        $lastPage = (int) ceil($total / $perPage);

        return array_merge([
            'items' => $items,
            'pagination' => [
                'total' => $total,
                'count' => is_countable($items) ? count($items) : 0,
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'hasMorePages' => $currentPage < $lastPage,
            ],
        ], $additionalData);
    }

    /**
     * Return a paginated JSON response with modern structure.
     */
    protected function modernPaginatedResponse(
        mixed $items,
        int $total,
        int $currentPage,
        int $perPage,
        string $message = 'Operación exitosa',
        string $serviceName = 'api-service',
        ?string $collectionName = null,
        array $additionalData = [],
        int $httpStatus = 200
    ): JsonResponse {
        $lastPage = (int) ceil($total / $perPage);
        $from = $total > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
        $to = min($from + $perPage - 1, $total);

        // Use a custom collection name or default to 'items'
        $dataKey = $collectionName ?? 'items';

        // Build pagination links
        $request = request();
        $baseUrl = $request->url();
        $queryParams = $request->query();

        $paginationLinks = [
            'first' => $this->buildPaginationUrl($baseUrl, $queryParams, 1),
            'prev' => $currentPage > 1 ? $this->buildPaginationUrl($baseUrl, $queryParams, $currentPage - 1) : null,
            'self' => $this->buildPaginationUrl($baseUrl, $queryParams, $currentPage),
            'next' => $currentPage < $lastPage ? $this->buildPaginationUrl($baseUrl, $queryParams, $currentPage + 1) : null,
            'last' => $this->buildPaginationUrl($baseUrl, $queryParams, $lastPage),
        ];

        return response()->json([
            'success' => true,
            'code' => $httpStatus,
            'message' => $message,
            'meta' => [
                'version' => config('api.version', '1.0.0'),
                'service' => $serviceName,
                'processDate' => Carbon::now()->utc()->toISOString(),
                'pagination' => [
                    'total' => $total,
                    'perPage' => $perPage,
                    'currentPage' => $currentPage,
                    'lastPage' => $lastPage,
                    'from' => $from,
                    'to' => $to,
                ],
                'links' => $paginationLinks,
            ],
            'data' => array_merge([$dataKey => $items], $additionalData),
        ], $httpStatus);
    }

    /**
     * Build pagination URL with query parameters
     */
    private function buildPaginationUrl(string $baseUrl, array $queryParams, int $page): string
    {
        $queryParams['page'] = $page;
        $queryString = http_build_query($queryParams);

        return "{$baseUrl}?{$queryString}";
    }

    /**
     * Build a simple collection response.
     */
    protected function buildCollectionData(
        mixed $items,
        ?string $collectionName = 'items',
        array $additionalData = []
    ): array {
        $data = [
            $collectionName => $items,
            'count' => is_countable($items) ? count($items) : 0,
        ];

        return array_merge($data, $additionalData);
    }

    /**
     * Build a single resource response.
     */
    protected function buildResourceData(
        mixed $resource,
        ?string $resourceName = null,
        array $additionalData = []
    ): array {
        if ($resourceName) {
            return array_merge([
                $resourceName => $resource,
            ], $additionalData);
        }

        return is_array($resource)
            ? array_merge($resource, $additionalData)
            : array_merge(['data' => $resource], $additionalData);
    }
}
