<?php

namespace App\Exceptions;

use App\Http\Resources\LocalAuthResponse;
use Auth0\SDK\Exception\ConfigurationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/v1/auth/*')) {
            return $this->renderLocalAuth($request, $exception);
        }

        // Evitar loop si Auth0 no está configurado
        if ($exception instanceof ConfigurationException) {
            return response('Auth0 configuration error: '.$exception->getMessage(), 500);
        }

        return parent::render($request, $exception);
    }

    public function report(Throwable $exception): void
    {
        if (app()->bound('request') && request()->is('api/v1/auth/*')) {
            if (! $exception instanceof LocalAuthException && ! $exception instanceof ValidationException
                && ! $exception instanceof OAuthServerException && ! $exception instanceof HttpExceptionInterface
                && ! $exception instanceof HttpResponseException) {
                Log::error('Local authentication request failed.', ['exception_type' => $exception::class]);
            }

            return;
        }

        parent::report($exception);
    }

    private function renderLocalAuth(Request $request, Throwable $exception): LocalAuthResponse
    {
        $service = match ($request->route()?->getName()) {
            'v1.auth.refresh' => 'refresh-user',
            'v1.auth.me' => 'current-user',
            default => 'login-user',
        };

        return match (true) {
            $exception instanceof HttpResponseException && $exception->getResponse() instanceof LocalAuthResponse => $exception->getResponse(),
            $exception instanceof LocalAuthException => new LocalAuthResponse(
                $exception->status, $exception->getMessage(), null, $service
            ),
            $exception instanceof ValidationException => new LocalAuthResponse(
                422, 'Los datos proporcionados no son válidos.', ['errors' => $exception->errors()], $service
            ),
            $exception instanceof OAuthServerException => new LocalAuthResponse(
                401, 'Credenciales o token de renovación inválidos.', null, $service
            ),
            $exception instanceof HttpExceptionInterface => new LocalAuthResponse(
                $exception->getStatusCode(), 'No se pudo procesar la solicitud.', null, $service, $exception->getHeaders()
            ),
            default => new LocalAuthResponse(
                503, 'El servicio de autenticación local no está disponible.', null, $service
            ),
        };
    }
}
