<?php

declare(strict_types=1);

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApiExceptionTestMiddleware
{
    public function __construct(private readonly Throwable $exception) {}

    public function handle(Request $request, Closure $next): Response
    {
        throw $this->exception;
    }
}

it('formats API HTTP exceptions before the controller runs', function (int $status): void {
    $controllerReached = false;
    $this->app->instance(ApiExceptionTestMiddleware::class, new ApiExceptionTestMiddleware(
        new HttpException($status, 'Sensitive internal details'),
    ));
    Route::get('/api/test-http-error', function () use (&$controllerReached): Response {
        $controllerReached = true;

        return response('Unexpected controller execution');
    })->middleware(ApiExceptionTestMiddleware::class)->name('test.http-error');

    $response = $this->get('/api/test-http-error')
        ->assertStatus($status)
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', $status)
        ->assertJsonPath('meta.service', 'http-error')
        ->assertJsonPath('data', null)
        ->assertJsonStructure([
            'success', 'code', 'message',
            'meta' => ['version', 'service', 'processDate'], 'data',
        ]);

    expect($controllerReached)->toBeFalse();
    expect($response->json('message'))->not->toContain('Sensitive internal details');
})->with([400, 401, 403, 404, 409, 422, 429, 500, 502, 503, 504]);

it('returns a modern 405 with the allowed methods without entering the controller', function (string $path): void {
    $controllerReached = false;
    Route::post($path, function () use (&$controllerReached): Response {
        $controllerReached = true;

        return response('Unexpected controller execution');
    });

    $this->get($path)
        ->assertStatus(405)
        ->assertHeader('Content-Type', 'application/json')
        ->assertHeader('Allow', 'POST')
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 405)
        ->assertJsonPath('data.allowedMethods', ['POST']);

    expect($controllerReached)->toBeFalse();
})->with(['/api/test-method', '/api/v1/auth/test-method']);

it('returns a modern 404 for an unknown API route', function (): void {
    $this->get('/api/test-route-that-does-not-exist')
        ->assertNotFound()
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 404);
});

it('normalizes framework exceptions raised by middleware', function (string $exceptionType, int $status): void {
    $exception = match ($exceptionType) {
        'authentication' => new AuthenticationException,
        'authorization' => new AuthorizationException,
        'model' => (new ModelNotFoundException)->setModel('TestModel', ['missing']),
        'validation' => ValidationException::withMessages(['reference' => ['The reference field is required.']]),
        'unexpected' => new RuntimeException('Sensitive database credentials'),
    };
    $this->app->instance(ApiExceptionTestMiddleware::class, new ApiExceptionTestMiddleware($exception));
    Route::get('/api/test-framework-error', fn (): Response => response('Unexpected controller execution'))
        ->middleware(ApiExceptionTestMiddleware::class);

    $response = $this->get('/api/test-framework-error')
        ->assertStatus($status)
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', $status);

    if ($exceptionType === 'validation') {
        $response->assertJsonPath('data.errors.reference', ['The reference field is required.']);
    }

    expect($response->getContent())->not->toContain('Sensitive database credentials');
})->with([
    'authentication' => ['authentication', 401],
    'authorization' => ['authorization', 403],
    'missing model' => ['model', 404],
    'validation' => ['validation', 422],
    'unexpected failure' => ['unexpected', 500],
]);

it('preserves rate limit headers and retry information', function (): void {
    Route::get('/api/test-throttle', fn (): Response => response('OK'))->middleware('throttle:1,1');

    $this->get('/api/test-throttle')->assertOk();
    $response = $this->get('/api/test-throttle')
        ->assertStatus(429)
        ->assertJsonPath('code', 429)
        ->assertHeader('Retry-After')
        ->assertHeader('X-RateLimit-Limit', '1')
        ->assertHeader('X-RateLimit-Remaining', '0');

    expect($response->json('data.retryAfterSeconds'))->toBe((int) $response->headers->get('Retry-After'));
});

it('preserves service unavailable headers', function (): void {
    Route::get('/api/test-unavailable', function (): never {
        throw new HttpException(503, 'Maintenance', null, ['Retry-After' => '120']);
    });

    $this->get('/api/test-unavailable')
        ->assertStatus(503)
        ->assertHeader('Retry-After', '120')
        ->assertJsonPath('code', 503);
});

it('recognizes routes in the API middleware group outside the API prefix', function (): void {
    Route::get('/test-api-group', fn () => abort(409))->middleware('api');

    $this->get('/test-api-group')->assertStatus(409)->assertJsonPath('code', 409);
});

it('keeps web error rendering unchanged even when JSON is requested', function (): void {
    Route::get('/test-web-error', fn () => abort(403));

    $this->get('/test-web-error')
        ->assertForbidden()
        ->assertHeader('Content-Type', 'text/html; charset=utf-8');

    $this->getJson('/test-web-error')
        ->assertForbidden()
        ->assertJsonMissingPath('success')
        ->assertJsonMissingPath('meta');
});

it('preserves explicitly provided HTTP responses', function (): void {
    Route::get('/api/test-explicit-response', function (): never {
        throw new HttpResponseException(response()->json(['existing' => 'contract'], 409));
    });

    $this->get('/api/test-explicit-response')
        ->assertStatus(409)
        ->assertExactJson(['existing' => 'contract']);
});

it('does not expose internal errors when debug mode is enabled', function (): void {
    config()->set('app.debug', true);
    Route::get('/api/test-debug-error', function (): never {
        throw new RuntimeException('Sensitive internal details');
    });

    $this->get('/api/test-debug-error')
        ->assertStatus(500)
        ->assertJsonPath('message', 'Error interno del servidor')
        ->assertJsonMissingPath('exception')
        ->assertJsonMissingPath('trace');
});
