<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LogoutController;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\SDK\Configuration\SdkConfiguration;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    config()->set([
        'auth0.guards.default' => [
            'domain' => 'logout-test.auth0.com',
            'clientId' => 'logout-test-client',
            'clientSecret' => bin2hex(random_bytes(32)),
        ],
        'auth0.guards.web' => [
            'strategy' => SdkConfiguration::STRATEGY_REGULAR,
            'cookieSecret' => bin2hex(random_bytes(32)),
            'redirectUri' => 'https://application.example.test/callback',
        ],
        'auth0.routes.after_logout' => '/signed-out',
    ]);
});

it('registers a single web logout route without requiring an active local session', function (): void {
    $routes = collect(Route::getRoutes())->filter(
        static fn ($route): bool => $route->getName() === 'logout'
    );

    expect($routes)->toHaveCount(1);
    expect($routes->first()->getActionName())->toBe(LogoutController::class);
    expect($routes->first()->methods())->toContain('GET');
    expect($routes->first()->gatherMiddleware())->not->toContain('auth');
});

it('clears authenticated guard credentials and local session before redirecting to Auth0', function (): void {
    $guard = Auth::guard('auth0-session');
    $guard->setCredential(CredentialEntity::create(
        user: new GenericUser(['id' => 123, 'name' => 'Logout Test User']),
    ));
    $this->withSession([
        '_token' => 'previous-csrf-token',
        'private-data' => 'must-be-cleared',
        'auth0_transient' => json_encode(['state' => 'previous-state']),
    ]);
    $guard->sdk()->setRefreshToken(bin2hex(random_bytes(32)));
    expect(session()->has('auth0_session'))->toBeTrue();
    $previousSessionId = session()->getId();

    $response = $this->get(route('logout'));

    $response->assertRedirect()
        ->assertSessionMissing('private-data')
        ->assertSessionMissing('auth0_session')
        ->assertSessionMissing('auth0_transient')
        ->assertHeader('Pragma', 'no-cache')
        ->assertHeader('Expires', '0');
    expect($response->headers->get('Cache-Control'))->toContain('no-store', 'no-cache');
    expect(session()->getId())->not->toBe($previousSessionId);
    expect(session()->token())->toBeString()->not->toBe('previous-csrf-token');
    expect($guard->getCredential())->toBeNull();
    expect($guard->sdk()->getCredentials())->toBeNull();
    expect($guard->sdk()->getRefreshToken())->toBeNull();

    $location = $response->headers->get('Location');
    expect(parse_url($location, PHP_URL_HOST))->toBe('logout-test.auth0.com');
    expect(parse_url($location, PHP_URL_PATH))->toBe('/v2/logout');
    parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
    expect($parameters['client_id'])->toBe('logout-test-client');
    expect($parameters['returnTo'])->toBe(route('login'));
    expect($parameters)->not->toHaveKey('federated');
});

it('still ends the provider session when the local session has expired', function (): void {
    $response = $this->get(route('logout'))->assertRedirect();

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))
        ->toBe('logout-test.auth0.com');
    expect(parse_url($response->headers->get('Location'), PHP_URL_PATH))
        ->toBe('/v2/logout');
    expect(Auth::guard('auth0-session')->getCredential())->toBeNull();
});

it('returns to login rather than the callback or another configured landing page', function (): void {
    config()->set([
        'auth0.routes.after_logout' => '/callback',
        'auth0.routes.index' => '/welcome',
    ]);

    $response = $this->get(route('logout'))->assertRedirect();
    parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $parameters);

    expect($parameters['returnTo'])->toBe(route('login'));
    expect($parameters['returnTo'])->not->toBe(route('callback'));
});

it('does not accept a post-logout destination supplied by the browser', function (): void {
    config()->set([
        'auth0.routes.after_logout' => null,
        'auth0.routes.index' => null,
    ]);

    $response = $this->get(route('logout', [
        'returnTo' => 'https://untrusted.example.test',
    ]))->assertRedirect();
    parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $parameters);

    expect($parameters['returnTo'])->toBe(route('login'));
});

it('starts a new login flow after returning from the provider logout', function (): void {
    $logout = $this->get(route('logout'))->assertRedirect();
    parse_str(parse_url($logout->headers->get('Location'), PHP_URL_QUERY), $logoutParameters);

    $login = $this->get($logoutParameters['returnTo'])->assertRedirect();
    $location = $login->headers->get('Location');
    parse_str(parse_url($location, PHP_URL_QUERY), $loginParameters);

    expect(parse_url($location, PHP_URL_HOST))->toBe('logout-test.auth0.com');
    expect(parse_url($location, PHP_URL_PATH))->toBe('/authorize');
    expect($loginParameters['redirect_uri'])->toBe('https://application.example.test/callback');
});
