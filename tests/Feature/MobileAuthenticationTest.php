<?php

declare(strict_types=1);

use App\Auth\MobileUserRepository;
use App\Services\Security\MobileAuthConfiguration;
use App\User;
use Auth0\Laravel\Controllers\LoginController;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Guard;

beforeEach(function (): void {
    $this->prepareMobileAuthentication();
});
it('does not return roles or permissions belonging to a different permission guard', function (): void {
    $id = $this->localUser();
    DB::table('roles')->insert(['id' => 1, 'name' => 'API_ONLY', 'guard_name' => 'api']);
    DB::table('permissions')->insert([
        ['id' => 1, 'name' => 'receipts.read', 'guard_name' => 'web'],
        ['id' => 2, 'name' => 'api.only', 'guard_name' => 'api'],
    ]);
    DB::table('model_has_roles')->insert(['role_id' => 1, 'model_type' => User::class, 'model_id' => $id]);
    DB::table('model_has_permissions')->insert([
        ['permission_id' => 1, 'model_type' => User::class, 'model_id' => $id],
        ['permission_id' => 2, 'model_type' => User::class, 'model_id' => $id],
    ]);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertOk()->assertJsonPath('data.roles', [])
        ->assertJsonPath('data.permissions', ['receipts.read']);
});

it('returns only a safe local profile for a valid Auth0 access token', function (): void {
    $id = $this->localUser();
    DB::table('securityaccesswarehouses')->insert([
        ['username' => 'mobile.user', 'centerid' => 'CD01', 'warehouseid' => 'WH01'],
        ['username' => 'another.user', 'centerid' => 'CD02', 'warehouseid' => 'WH02'],
    ]);

    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertOk()
        ->assertExactJson(['data' => [
            'id' => $id,
            'uuiduser' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
            'username' => 'mobile.user',
            'name' => 'MOBILE USER',
            'email' => 'mobile.user@example.test',
            'roles' => [],
            'permissions' => [],
            'warehouse_access' => [['centerid' => 'CD01', 'warehouseid' => 'WH01']],
        ]])
        ->assertHeader('Cache-Control', 'no-store, private')
        ->assertHeaderMissing('Set-Cookie');
});

it('requires a bearer token even without an Accept JSON header', function (): void {
    $this->get('/api/mobile/auth/me')
        ->assertUnauthorized()
        ->assertJsonPath('error', 'UNAUTHENTICATED')
        ->assertHeader('WWW-Authenticate', 'Bearer')
        ->assertHeader('Cache-Control', 'no-store, private')
        ->assertHeaderMissing('Location');
});

it('fails closed when mobile configuration is unavailable', function (string $key, mixed $value): void {
    config()->set('mobile_auth.'.$key, $value);
    $this->getJson('/api/mobile/auth/me')
        ->assertStatus(503)
        ->assertJsonPath('error', 'MOBILE_AUTH_UNAVAILABLE');
})->with([
    'disabled' => ['enabled', false],
    'missing domain' => ['domain', ''],
    'domain containing scheme' => ['domain', 'https://example.test'],
    'missing audience' => ['audience', ''],
    'management audience' => ['audience', 'https://example.test/api/v2/'],
    'ID token audience' => ['audience', 'flutter-native-client'],
    'missing clients' => ['allowed_client_ids', []],
]);

it('rejects invalid signed token claims', function (array $claims): void {
    $this->localUser();
    $this->withToken($this->accessToken($claims))->getJson('/api/mobile/auth/me')
        ->assertUnauthorized()
        ->assertJsonPath('error', 'UNAUTHENTICATED');
})->with([
    'wrong issuer' => [['iss' => 'https://another.auth0.example.test/']],
    'wrong audience' => [['aud' => 'https://another.example.test/api']],
    'native ID token' => [['aud' => 'flutter-native-client']],
    'expired' => [fn (): array => ['exp' => time() - 100]],
    'future issued' => [fn (): array => ['iat' => time() + 300]],
    'not yet valid' => [fn (): array => ['nbf' => time() + 300]],
    'invalid issued type' => [['iat' => 'invalid']],
    'missing issued' => [['iat' => null]],
    'invalid expiry type' => [['exp' => 'invalid']],
    'unknown client' => [['azp' => 'another-client']],
    'missing client' => [['azp' => null]],
    'contradictory clients' => [['client_id' => 'another-client']],
    'missing subject' => [['sub' => null]],
    'machine subject' => [['sub' => 'flutter-native-client@clients']],
    'machine grant' => [['gty' => 'client-credentials']],
]);

it('rejects unsigned, malformed or tampered tokens', function (string $token): void {
    $this->withToken($token)->getJson('/api/mobile/auth/me')->assertUnauthorized();
})->with([
    'malformed' => 'not-a-jwt',
    'unsigned' => 'eyJhbGciOiJub25lIn0.eyJzdWIiOiJhdXRoMHx1c2VyIn0.',
]);

it('rejects a modified signature', function (): void {
    $token = $this->accessToken();
    $position = strrpos($token, '.') + 1;
    $token[$position] = $token[$position] === 'A' ? 'B' : 'A';
    $this->withToken($token)->getJson('/api/mobile/auth/me')->assertUnauthorized();
});

it('requires the exact mobile scope', function (mixed $scope): void {
    $this->localUser();
    $this->withToken($this->accessToken(['scope' => $scope]))->getJson('/api/mobile/auth/me')
        ->assertForbidden()
        ->assertJsonPath('error', 'MOBILE_ACCESS_DENIED');
})->with(['openid', 'mobile:access-extra', '', null]);

it('denies access to inactive, unlinked or deleted local users', function (array $attributes): void {
    $this->localUser($attributes);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertForbidden()
        ->assertJsonPath('error', 'MOBILE_ACCESS_DENIED');
})->with([
    'inactive' => [['statusid' => 'D']],
    'different status' => [['statusid' => 'P']],
    'unlinked with same email' => [['auth0' => null]],
    'different subject with same email' => [['auth0' => 'auth0|another-user']],
    'deleted' => [['deleted_at' => '2026-01-01 00:00:00']],
]);

it('does not provision unknown users or accept a subject from the request', function (): void {
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me?auth0=auth0%7Cmobile-user')
        ->assertForbidden();
    expect(DB::table('users')->count())->toBe(0);
});

it('denies ambiguous subject mappings including a deleted duplicate', function (bool $deleted): void {
    $this->localUser();
    $this->localUser(['username' => 'duplicate.user', 'deleted_at' => $deleted ? '2026-01-01 00:00:00' : null]);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')->assertForbidden();
})->with([false, true]);

it('rejects a token signed by a key that is not trusted by the configured issuer', function (): void {
    $this->withToken($this->accessToken([], 'untrusted-key'))->getJson('/api/mobile/auth/me')
        ->assertUnauthorized();
});

it('rejects tokens with a different signing algorithm', function (): void {
    $encode = static fn (string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    $token = $encode(json_encode(['alg' => 'HS256'], JSON_THROW_ON_ERROR))
        .'.'.$encode(json_encode(['sub' => 'auth0|mobile-user'], JSON_THROW_ON_ERROR))
        .'.'.$encode('invalid-signature');
    $this->withToken($token)->getJson('/api/mobile/auth/me')->assertUnauthorized();
});
it('checks local account status again on every request', function (): void {
    $id = $this->localUser();
    $token = $this->accessToken();
    $this->withToken($token)->getJson('/api/mobile/auth/me')->assertOk();
    DB::table('users')->where('id', $id)->update(['statusid' => 'D']);
    $this->withToken($token)->getJson('/api/mobile/auth/me')->assertForbidden();
});

it('supports the client_id claim and multiple audiences in access tokens', function (): void {
    $this->localUser();
    $this->withToken($this->accessToken([
        'aud' => ['https://audittool.example.test/api/mobile', 'https://mobile.auth0.example.test/userinfo'],
        'azp' => null,
        'client_id' => 'flutter-native-client',
    ]))->getJson('/api/mobile/auth/me')->assertOk();
});

it('fetches rotated signing keys when the cached JWKS does not contain the new kid', function (): void {
    $this->localUser();
    $handler = $this->fakeJwks([$this->jwksResponse(), $this->jwksResponse('rotated-key')]);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')->assertOk();
    $this->withToken($this->accessToken([], 'rotated-key'))->getJson('/api/mobile/auth/me')->assertOk();
    expect($handler->count())->toBe(0);
});

it('returns a safe service error if the JWKS server cannot be reached', function (): void {
    $this->fakeJwks([new ConnectException('private upstream detail', new Request('GET', 'https://mobile.auth0.example.test/.well-known/jwks.json'))]);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertStatus(503)
        ->assertExactJson(['error' => 'MOBILE_AUTH_UNAVAILABLE', 'message' => 'Autenticación móvil no disponible.']);
});

it('keeps web permissions usable while the mobile guard is selected', function (): void {
    $id = $this->localUser();
    DB::table('roles')->insert(['id' => 1, 'name' => 'OPERATOR', 'guard_name' => 'web']);
    DB::table('permissions')->insert(['id' => 1, 'name' => 'receipts.read', 'guard_name' => 'web']);
    DB::table('model_has_roles')->insert(['role_id' => 1, 'model_type' => User::class, 'model_id' => $id]);
    DB::table('role_has_permissions')->insert(['role_id' => 1, 'permission_id' => 1]);

    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertOk()
        ->assertJsonPath('data.roles', ['OPERATOR'])
        ->assertJsonPath('data.permissions', ['receipts.read']);

    $user = User::findOrFail($id);
    $this->app['auth']->shouldUse('auth0-mobile');
    expect(Guard::getDefaultName($user))->toBe('web');
    expect($user->hasPermissionTo('receipts.read'))->toBeTrue();
    expect(Gate::forUser($user)->allows('receipts.read'))->toBeTrue();
});

it('does not reuse the previous user for a different token or an unauthenticated request', function (): void {
    $this->localUser();
    $this->localUser(['username' => 'second.user', 'auth0' => 'auth0|second-user']);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertOk()->assertJsonPath('data.username', 'mobile.user');
    $this->withToken($this->accessToken(['sub' => 'auth0|second-user']))->getJson('/api/mobile/auth/me')
        ->assertOk()->assertJsonPath('data.username', 'second.user');
    $this->flushHeaders()->getJson('/api/mobile/auth/me')->assertUnauthorized();
});

it('preserves authorized center and warehouse pairs without widening their scope', function (): void {
    $this->localUser();
    DB::table('securityaccesswarehouses')->insert([
        ['username' => 'mobile.user', 'centerid' => 'CD01', 'warehouseid' => 'WH01'],
        ['username' => 'mobile.user', 'centerid' => 'CD01', 'warehouseid' => 'WH01'],
        ['username' => 'mobile.user', 'centerid' => 'CD02', 'warehouseid' => 'WH02'],
        ['username' => 'another.user', 'centerid' => 'CD03', 'warehouseid' => 'WH03'],
    ]);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')
        ->assertOk()->assertJsonPath('data.warehouse_access', [
            ['centerid' => 'CD01', 'warehouseid' => 'WH01'],
            ['centerid' => 'CD02', 'warehouseid' => 'WH02'],
        ]);
});
it('preserves web and Passport configuration and does not retain the mobile user', function (): void {
    $this->localUser();
    $previous = $this->app['auth']->getDefaultDriver();
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')->assertOk();
    expect($this->app['auth']->getDefaultDriver())->toBe($previous);
    expect($this->app['auth']->guard('auth0-mobile')->getCredential())->toBeNull();
    expect(config('auth.guards.api.driver'))->toBe('passport');
    expect(config('auth.guards.auth0-session.driver'))->toBe('auth0.authenticator');
    expect(Route::getRoutes()->getByName('login')->getActionName())->toBe(LoginController::class);
    expect(Route::getRoutes()->getByName('passport.token'))->not->toBeNull();
});

it('does not inherit web SDK secrets or configuration', function (): void {
    $configuration = $this->app['auth']->guard('auth0-mobile')->sdk()->configuration();
    expect($configuration->getClientSecret())->toBeNull();
    expect($configuration->getTokenAlgorithm())->toBe('RS256');
    expect($configuration->getAudience())->toBe(['https://audittool.example.test/api/mobile']);
    expect($this->app->make(MobileAuthConfiguration::class)->isConfigured())->toBeTrue();
    expect($this->app->make(MobileUserRepository::class)->fromSession(['sub' => 'auth0|mobile-user']))->toBeNull();
});

it('does not expose password login or token exchange endpoints', function (): void {
    $uris = collect(Route::getRoutes())->map(fn ($route): string => $route->uri())->all();
    expect($uris)->not->toContain('api/mobile/auth/login', 'api/mobile/auth/refresh', 'api/mobile/auth/logout');
});

it('rate limits mobile requests by IP without using another authentication guard', function (): void {
    for ($attempt = 0; $attempt < 60; $attempt++) {
        $this->get('/api/mobile/auth/me')->assertUnauthorized();
    }
    $this->get('/api/mobile/auth/me')
        ->assertTooManyRequests()
        ->assertJsonPath('error', 'TOO_MANY_REQUESTS')
        ->assertHeader('Cache-Control', 'no-store, private');
});
