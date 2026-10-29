<?php

declare(strict_types=1);

use App\Models\Security\Users\StorageUserDevice;
use App\Repositories\Inventory\InventoryPartNumberRepository;
use App\Repositories\Security\LocalPassportClientRepository;
use App\Services\Security\LocalRefreshTokenDecoder;
use App\Services\Security\TokenManagementService;
use App\User;
use Auth0\Laravel\Controllers\CallbackController;
use Auth0\Laravel\Controllers\LoginController;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

function localPassportTestKeys(): array
{
    static $keys;
    if ($keys === null) {
        $private = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($private, $pem);
        $keys = [$pem, openssl_pkey_get_details($private)['key']];
    }

    return $keys;
}

beforeEach(function (): void {
    $this->prepareAuthenticationSchema();
    [$private, $public] = localPassportTestKeys();
    config()->set([
        'app.key' => 'base64:'.base64_encode(random_bytes(32)),
        'passport.private_key' => $private,
        'passport.public_key' => $public,
        'hashing.bcrypt.rounds' => 4,
        'local_auth.token_app' => bin2hex(random_bytes(32)),
    ]);
    Schema::table('users', function (Blueprint $table): void {
        $table->text('configurationflag')->nullable();
        $table->dateTime('password_changed_at')->nullable();
        $table->timestamps();
    });
    Schema::table('securityaccesswarehouses', function (Blueprint $table): void {
        $table->string('defaultlevel')->nullable();
    });
    Schema::create('systemcenterdistributions', function (Blueprint $table): void {
        $table->string('centerid')->primary();
        $table->string('name');
        $table->string('statusid')->default('E');
        $table->text('mobileflags')->nullable();
    });
    Schema::create('systemwarehouses', function (Blueprint $table): void {
        $table->string('warehouseid')->primary();
        $table->string('centerid');
        $table->string('statusid')->default('E');
        $table->text('configurationflag')->nullable();
    });
    Schema::create('securitydeviceforuser', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('username');
        $table->string('serialnumber', 60);
        $table->dateTime('created_at');
    });
    Schema::create('oauth_clients', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('secret')->nullable();
        $table->string('provider')->nullable();
        $table->text('redirect');
        $table->boolean('personal_access_client');
        $table->boolean('password_client');
        $table->boolean('revoked');
        $table->timestamps();
    });
    Schema::create('oauth_access_tokens', function (Blueprint $table): void {
        $table->string('id', 100)->primary();
        $table->bigInteger('user_id')->nullable();
        $table->bigInteger('client_id');
        $table->string('name')->nullable();
        $table->text('scopes')->nullable();
        $table->boolean('revoked');
        $table->dateTime('expires_at');
        $table->timestamps();
    });
    Schema::create('oauth_refresh_tokens', function (Blueprint $table): void {
        $table->string('id', 100)->primary();
        $table->string('access_token_id', 100);
        $table->boolean('revoked');
        $table->dateTime('expires_at');
    });
    Schema::create('token_metadata', function (Blueprint $table): void {
        $table->id();
        $table->string('token_id', 100)->unique();
        $table->bigInteger('user_id');
        $table->string('device_fingerprint', 64);
        $table->string('ip_address', 45);
        $table->text('user_agent')->nullable();
        $table->dateTime('expires_at');
        $table->dateTime('last_used_at');
        $table->boolean('is_revoked')->default(false);
        $table->dateTime('revoked_at')->nullable();
        $table->text('additional_claims')->nullable();
        $table->timestamps();
    });
    Schema::create('authentication_audit_logs', function (Blueprint $table): void {
        $table->id();
        $table->string('username');
        $table->bigInteger('user_id')->nullable();
        $table->string('ip_address', 45);
        $table->boolean('successful');
        $table->string('reason')->nullable();
        $table->string('request_method')->nullable();
        $table->string('request_path')->nullable();
        $table->string('session_id')->nullable();
        $table->timestamps();
    });
    (require database_path('migrations/2026_10_29_160900_add_local_session_security_to_existing_token_tables.php'))->up();
    $client = $this->app->make(LocalPassportClientRepository::class)->prepare(
        config('local_auth.token_app'), $this->app->make(Hasher::class)
    );
    config()->set('local_auth.client_id', (string) $client->id);
    $this->withHeader('X-App-Token', config('local_auth.token_app'));
    $this->localUser([
        'password' => Hash::make('local-test-password'),
        'auth0' => null,
        'configurationflag' => json_encode(['USR-01']),
    ]);
    DB::table('systemcenterdistributions')->insert([
        ['centerid' => 'BRIDCUE', 'name' => 'BRIDGESTONE CUERNAVACA', 'mobileflags' => json_encode(['APP-03', 'APP-04'])],
        ['centerid' => 'OTHER', 'name' => 'OTHER CENTER', 'mobileflags' => null],
    ]);
    DB::table('securityaccesswarehouses')->insert([
        ['username' => 'mobile.user', 'centerid' => 'OTHER', 'warehouseid' => 'OTHER-WH', 'defaultlevel' => null],
        ['username' => 'mobile.user', 'centerid' => 'BRIDCUE', 'warehouseid' => 'MX10APT', 'defaultlevel' => 'S'],
    ]);
    DB::table('systemwarehouses')->insert([
        ['warehouseid' => 'MX10APT', 'centerid' => 'BRIDCUE', 'configurationflag' => json_encode(['SWH-01', 'SWH-02'])],
        ['warehouseid' => 'NOT-ALLOWED', 'centerid' => 'BRIDCUE', 'configurationflag' => json_encode(['SWH-03'])],
        ['warehouseid' => 'OTHER-WH', 'centerid' => 'OTHER', 'configurationflag' => null],
    ]);
});

it('keeps an existing web user untouched when another local user logs in', function (): void {
    $webUser = new User;
    $webUser->forceFill(['id' => 999, 'name' => 'Web User', 'username' => 'web.user']);
    $this->app['auth']->guard('web')->setUser($webUser);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    expect($this->app['auth']->guard('web')->user())->toBe($webUser);
    expect($this->app['auth']->getDefaultDriver())->toBe('web');
});

it('does not accept OAuth client, provider or scope overrides from the login body', function (): void {
    $login = $this->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password',
        'client_id' => 999, 'grant_type' => 'client_credentials', 'scope' => '*', 'provider' => 'users',
    ])->assertOk();
    $token = (new Parser(new JoseEncoder))->parse($login->json('data.auth.accessToken'));
    expect($token->claims()->get('aud'))->toBe([(string) config('local_auth.client_id')]);
    expect($token->claims()->get('scopes'))->toBe([]);
});

it('normalizes null flags and excludes inaccessible or inactive warehouses', function (): void {
    DB::table('users')->update(['configurationflag' => null]);
    DB::table('systemcenterdistributions')->update(['mobileflags' => null]);
    DB::table('systemwarehouses')->where('warehouseid', 'MX10APT')->update(['statusid' => 'D']);
    $response = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $response->assertJsonPath('data.parameters.app', [])->assertJsonPath('data.parameters.user', []);
    expect(json_decode($response->getContent())->data->parameters->warehouse)->toBeInstanceOf(stdClass::class);
    expect($response->json('data.parameters.warehouse'))->toBe([]);
});

it('selects the first access deterministically when no default is marked', function (): void {
    DB::table('securityaccesswarehouses')->update(['defaultlevel' => null]);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])
        ->assertOk()->assertJsonPath('data.user.centerId', 'OTHER');
});

it('does not trim passwords and validates non-string usernames safely', function (): void {
    DB::table('users')->update(['password' => Hash::make(' password with spaces ')]);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => ' password with spaces '])->assertOk();
    $this->postJson('/api/v1/auth/login', ['username' => ['mobile.user'], 'password' => 'anything'])->assertUnprocessable();
});

it('requires TOKEN_APP on refresh and denies a disabled account on the bearer endpoint', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->flushHeaders()->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    $this->withHeader('X-App-Token', config('local_auth.token_app'));
    DB::table('users')->update(['statusid' => 'D']);
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
});

it('prepares an isolated environment idempotently without revealing or rotating its secret', function (): void {
    $directory = sys_get_temp_dir().'/audittool-local-auth-'.bin2hex(random_bytes(8));
    mkdir($directory, 0700);
    $path = $directory.'/.env';
    $secret = config('local_auth.token_app');
    file_put_contents($path, "APP_NAME=unchanged\nTOKEN_APP=".$secret."\n");
    $originalPath = $this->app->environmentPath();
    $this->app->useEnvironmentPath($directory);
    try {
        $this->artisan('auth:setup-local', ['--no-interaction' => true])
            ->expectsOutputToContain('Login local configurado.')->assertSuccessful();
        $first = file_get_contents($path);
        $this->artisan('auth:setup-local', ['--no-interaction' => true])
            ->expectsOutputToContain('Login local configurado.')->assertSuccessful();
        expect(file_get_contents($path))->toBe($first);
        expect(Dotenv\Dotenv::parse($first)['TOKEN_APP'])->toBe($secret);
        expect(Dotenv\Dotenv::parse($first)['APP_NAME'])->toBe('unchanged');
        expect(DB::table('oauth_clients')->count())->toBe(1);
    } finally {
        $this->app->useEnvironmentPath($originalPath);
        unlink($path);
        rmdir($directory);
    }
});

it('issues native Passport tokens using local username and password without an Auth0 identity', function (): void {
    $response = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password']);
    $response->assertOk()->assertJsonPath('success', true)->assertJsonPath('code', 200)
        ->assertJsonPath('message', 'Sesión iniciada correctamente')
        ->assertJsonPath('meta.version', '1.0.0')->assertJsonPath('meta.service', 'login-user')
        ->assertJsonPath('data.user', ['name' => 'MOBILE USER', 'centerId' => 'BRIDCUE', 'centerName' => 'BRIDGESTONE CUERNAVACA'])
        ->assertJsonPath('data.auth.tokenType', 'Bearer')->assertJsonPath('data.auth.expiresIn', 900)
        ->assertJsonPath('data.parameters.app', ['APP-03', 'APP-04'])
        ->assertJsonPath('data.parameters.user', ['USR-01'])
        ->assertJsonPath('data.parameters.warehouse', ['MX10APT' => ['SWH-01', 'SWH-02']])
        ->assertJsonPath('data.device', ['registered' => false, 'is_new' => false, 'warning' => 'No se proporcionó número de serie del dispositivo'])
        ->assertHeader('Cache-Control', 'no-store, private')->assertHeaderMissing('Set-Cookie');
    $token = (new Parser(new JoseEncoder))->parse($response->json('data.auth.accessToken'));
    expect($token->claims()->get('exp')->getTimestamp() - $token->claims()->get('iat')->getTimestamp())->toBe(900);
    expect($response->json('data.auth.expiresAt'))->toBe(Carbon::instance($token->claims()->get('exp'))->utc()->format('Y-m-d H:i:s'));
    expect(DB::table('oauth_access_tokens')->count())->toBe(1);
    expect(DB::table('oauth_refresh_tokens')->count())->toBe(1);
    expect(DB::table('oauth_refresh_tokens')->value('id'))->not->toBe($response->json('data.auth.refreshToken'));
    expect(DB::table('oauth_access_tokens')->value('scopes'))->toBe('[]');
});

it('requires the application token in the header rather than query or JSON', function (): void {
    $this->flushHeaders()->postJson('/api/v1/auth/login?TOKEN_APP='.config('local_auth.token_app'), [
        'username' => 'mobile.user', 'password' => 'local-test-password', 'TOKEN_APP' => config('local_auth.token_app'),
    ])->assertUnauthorized()->assertJsonPath('success', false);
    $this->withHeader('X-App-Token', 'incorrect')->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password',
    ])->assertUnauthorized();
    expect(DB::table('oauth_access_tokens')->count())->toBe(0);
});

it('fails closed when TOKEN_APP or the local client is unavailable', function (string $key, mixed $value): void {
    config()->set('local_auth.'.$key, $value);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])
        ->assertStatus(503)->assertJsonPath('success', false);
})->with([['token_app', ''], ['token_app', 'short'], ['client_id', null], ['client_id', 'invalid']]);

it('rejects invalid local credentials and disabled or duplicate users consistently', function (array $changes): void {
    $username = $changes['login'] ?? 'mobile.user';
    if (isset($changes['user'])) {
        DB::table('users')->update($changes['user']);
    }
    if ($changes['duplicate'] ?? false) {
        $this->localUser(['username' => 'mobile.user', 'password' => Hash::make('local-test-password')]);
    }
    $this->postJson('/api/v1/auth/login', [
        'username' => $username, 'password' => $changes['password'] ?? 'local-test-password',
    ])->assertUnauthorized()->assertJsonPath('message', 'Credenciales inválidas o usuario no habilitado.');
    expect(DB::table('oauth_access_tokens')->count())->toBe(0);
})->with([
    'unknown user' => [['login' => 'unknown']],
    'wrong password' => [['password' => 'wrong-password']],
    'inactive' => [['user' => ['statusid' => 'D']]],
    'pending' => [['user' => ['statusid' => 'P']]],
    'deleted' => [['user' => ['deleted_at' => '2026-01-01 00:00:00']]],
    'invalid password hash' => [['user' => ['password' => 'plaintext-is-not-accepted']]],
    'duplicate' => [['duplicate' => true]],
]);

it('returns JSON validation failures without an Accept header', function (): void {
    $this->post('/api/v1/auth/login', [])->assertUnprocessable()->assertJsonPath('code', 422)
        ->assertJsonPath('meta.service', 'login-user');
    expect(DB::table('oauth_access_tokens')->count())->toBe(0);
});

it('rolls back token issuance if there is no authorized active distribution center', function (): void {
    DB::table('systemcenterdistributions')->update(['statusid' => 'D']);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertForbidden();
    expect(DB::table('oauth_access_tokens')->count())->toBe(0);
    expect(DB::table('oauth_refresh_tokens')->count())->toBe(0);
});

it('returns local direct and role permissions without changing their guard or morph type', function (): void {
    $id = DB::table('users')->value('id');
    DB::table('roles')->insert(['id' => 1, 'name' => 'APP_OPERATOR', 'guard_name' => 'web']);
    DB::table('permissions')->insert([
        ['id' => 1, 'name' => 'EMBARQUESAPP', 'guard_name' => 'web'],
        ['id' => 2, 'name' => 'RECIBOAPP', 'guard_name' => 'web'],
    ]);
    DB::table('model_has_roles')->insert(['role_id' => 1, 'model_type' => User::class, 'model_id' => $id]);
    DB::table('role_has_permissions')->insert(['role_id' => 1, 'permission_id' => 1]);
    DB::table('model_has_permissions')->insert(['permission_id' => 2, 'model_type' => User::class, 'model_id' => $id]);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])
        ->assertOk()->assertJsonPath('data.permissions', ['EMBARQUESAPP', 'RECIBOAPP']);
});

it('registers serial numbers against the numeric user ID idempotently', function (): void {
    $data = ['username' => 'mobile.user', 'password' => 'local-test-password', 'serialnumber' => 'DEVICE-001'];
    $this->postJson('/api/v1/auth/login', $data)->assertOk()
        ->assertJsonPath('data.device.registered', true)->assertJsonPath('data.device.is_new', true);
    $this->postJson('/api/v1/auth/login', $data)->assertOk()->assertJsonPath('data.device.is_new', false);
    expect(DB::table('securitydeviceforuser')->count())->toBe(1);
    expect(DB::table('securitydeviceforuser')->value('username'))->toBe(DB::table('users')->value('id'));
    expect(DB::table('oauth_access_tokens')->where('revoked', false)->count())->toBe(2);
});

it('validates the issued Passport bearer token on its isolated profile endpoint', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')
        ->assertOk()->assertJsonPath('meta.service', 'current-user')->assertJsonPath('data.user.centerId', 'BRIDCUE');
    expect($this->app['auth']->getDefaultDriver())->toBe('web');
    expect($this->app['auth']->guard('local-app')->hasUser())->toBeFalse();
});

it('accepts a login access token on products without redirecting to the web login', function (): void {
    $login = $this->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password',
    ])->assertOk();

    $this->mock(InventoryPartNumberRepository::class)
        ->shouldReceive('all')->once()->andReturnUsing(function () {
            expect($this->app['auth']->getDefaultDriver())->toBe('local-app');
            expect($this->app['auth']->user()->username)->toBe('mobile.user');

            return collect([(object) [
                'id' => 1, 'prtnum' => 'PRODUCT-001', 'lngdsc' => 'Test product',
                'untcas' => 1, 'palhgt' => 1, 'abccod' => 'A', 'shelflife' => 30, 'untpal' => 10,
            ]]);
        });

    $this->flushHeaders()->withToken($login->json('data.auth.accessToken'))
        ->get('/api/v1/catalogs/products')
        ->assertOk()->assertHeaderMissing('Location')
        ->assertJsonPath('success', true)
        ->assertJsonPath('code', 200)
        ->assertJsonPath('meta.service', 'products')
        ->assertJsonCount(1, 'data.products')
        ->assertJsonPath('data.products.0.partnumberId', 1)
        ->assertJsonPath('data.products.0.partnumberCode', 'PRODUCT-001')
        ->assertJsonPath('data.products.0.description', 'Test product');
});

it('rejects missing invalid or revoked access tokens on products', function (string $tokenState): void {
    $this->mock(InventoryPartNumberRepository::class)->shouldNotReceive('all');
    $this->flushHeaders();

    if ($tokenState === 'invalid') {
        $this->withToken('invalid-access-token');
    } elseif ($tokenState === 'revoked') {
        $login = $this->withHeader('X-App-Token', config('local_auth.token_app'))
            ->postJson('/api/v1/auth/login', [
                'username' => 'mobile.user', 'password' => 'local-test-password',
            ])->assertOk();
        DB::table('oauth_access_tokens')->update(['revoked' => true]);
        $this->flushHeaders()->withToken($login->json('data.auth.accessToken'));
    }

    $this->getJson('/api/v1/catalogs/products')
        ->assertUnauthorized()->assertHeaderMissing('Location');
})->with(['missing', 'invalid', 'revoked']);

it('revokes the entire reused refresh family without revoking another session', function (): void {
    $first = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $second = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $renewed = $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])
        ->assertOk()->assertJsonPath('meta.service', 'refresh-user');
    expect($renewed->json('data.auth.refreshToken'))->not->toBe($first->json('data.auth.refreshToken'));
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])->assertUnauthorized();
    $this->withToken($first->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->withToken($second->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
    $this->withToken($renewed->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
});

it('rejects refresh tokens after local account deactivation without issuing new tokens', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('users')->update(['statusid' => 'D']);
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    expect(DB::table('oauth_access_tokens')->count())->toBe(1);
});

it('keeps local Passport available after removing mobile Auth0 routes', function (): void {
    $local = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->withToken($local->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
    $this->getJson('/api/mobile/auth/me')->assertNotFound();
    expect(collect(Route::getRoutes())->filter(static fn ($route): bool => str_starts_with($route->uri(), 'api/mobile/auth')))->toBeEmpty();
    expect(DB::table('oauth_access_tokens')->where('revoked', false)->count())->toBe(1);
});

it('does not change Passport global settings or enable its global password grant', function (): void {
    $uuid = Passport::$clientUuids;
    $ttl = Passport::tokensExpireIn();
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    expect(Passport::$passwordGrantEnabled)->toBeFalse();
    expect(Passport::$clientUuids)->toBe($uuid);
    expect(Passport::tokensExpireIn())->toEqual($ttl);
    expect(config('auth.guards.api.driver'))->toBe('passport');
    expect(config('auth.guards.local-app.driver'))->toBe('local-passport');
    expect(config('auth.guards.auth0-mobile'))->toBeNull();
    expect(config('auth.providers.auth0-mobile-provider'))->toBeNull();
    expect(Route::getRoutes()->getByName('login')->getActionName())->toBe(LoginController::class);
    expect(Route::getRoutes()->getByName('callback')->getActionName())->toBe(CallbackController::class);
    expect(config('auth.guards.auth0-session.driver'))->toBe('auth0.authenticator');
});

it('limits repeated local credential attempts per account', function (): void {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'incorrect'])->assertUnauthorized();
    }
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'incorrect'])
        ->assertTooManyRequests()->assertJsonPath('code', 429);
    $this->postJson('/api/v1/auth/login', ['username' => 'another.user', 'password' => 'incorrect'])->assertUnauthorized();
});

it('rejects disabled accounts and changed passwords on all local resources', function (array $changes): void {
    $login = $this->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password',
    ])->assertOk();
    DB::table('users')->update($changes);
    $this->mock(InventoryPartNumberRepository::class)->shouldNotReceive('all');
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/catalogs/products')->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    expect(DB::table('oauth_refresh_tokens')->where('revoked', false)->count())->toBe(0);
})->with([
    'disabled' => [['statusid' => 'D']],
    'changed timestamp' => [['password_changed_at' => '2099-01-01 00:00:00']],
    'changed hash without timestamp' => [['password' => 'different-hash']],
]);

it('revokes native tokens immediately after a model password or status change', function (string $field): void {
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $user = User::firstOrFail();
    $user->forceFill([$field => $field === 'password' ? Hash::make('new-password') : 'D'])->save();
    expect(DB::table('oauth_access_tokens')->where('revoked', false)->count())->toBe(0);
    expect(DB::table('oauth_refresh_tokens')->where('revoked', false)->count())->toBe(0);
})->with(['password', 'statusid']);

it('logs out only the current family including native refresh tokens', function (): void {
    $first = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $second = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->withToken($first->json('data.auth.accessToken'))->postJson('/api/v1/auth/logout')
        ->assertOk()->assertJsonPath('meta.service', 'logout-user')->assertHeader('Cache-Control', 'no-store, private');
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])->assertUnauthorized();
    $this->withToken($second->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
});

it('logs out all local sessions without touching another OAuth client', function (): void {
    $first = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('oauth_access_tokens')->insert([
        'id' => 'other-client-token', 'user_id' => User::value('id'), 'client_id' => 999,
        'revoked' => false, 'expires_at' => now()->addDay(), 'scopes' => '[]',
    ]);
    $this->withToken($first->json('data.auth.accessToken'))->postJson('/api/v1/auth/logout-all')
        ->assertOk()->assertJsonPath('meta.service', 'logout-all-user');
    expect(DB::table('oauth_refresh_tokens')->where('revoked', false)->count())->toBe(0);
    expect(DB::table('oauth_access_tokens')->where('revoked', false)->pluck('id')->all())->toBe(['other-client-token']);
});

it('rejects missing bearer authentication for logout and does not trust submitted user identifiers', function (): void {
    $this->postJson('/api/v1/auth/logout-all', ['user_id' => User::value('id')])->assertUnauthorized();
    $this->postJson('/api/v1/auth/logout')->assertUnauthorized();
});

it('preserves the family start and absolute expiry while rotating tokens', function (): void {
    $first = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $original = DB::table('token_metadata')->first();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])->assertOk();
    $records = DB::table('token_metadata')->get();
    expect($records)->toHaveCount(2);
    expect($records[1]->session_family_id)->toBe($original->session_family_id);
    expect($records[1]->session_started_at)->toBe($original->session_started_at);
    expect($records[1]->session_expires_at)->toBe($original->session_expires_at);
    expect(DB::table('oauth_refresh_tokens')->whereNotNull('consumed_at')->count())->toBe(1);
    expect(DB::table('oauth_refresh_tokens')->whereNotNull('replaced_by_id')->count())->toBe(1);
});

it('rejects absolute expired sessions and expired refresh tokens', function (string $expiry): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    if ($expiry === 'absolute') {
        DB::table('token_metadata')->update(['session_expires_at' => '2000-01-01 00:00:00']);
        $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    } else {
        DB::table('oauth_refresh_tokens')->update(['expires_at' => '2000-01-01 00:00:00']);
    }
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    expect(DB::table('oauth_refresh_tokens')->where('revoked', false)->count())->toBe(0);
})->with(['absolute', 'refresh']);

it('fails closed for pre-upgrade sessions without security metadata', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('token_metadata')->delete();
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
});

it('does not revoke a valid session for an unauthentic refresh token', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $token = $login->json('data.auth.refreshToken');
    $token[20] = $token[20] === 'a' ? 'b' : 'a';
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $token])->assertUnauthorized();
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
});

it('revokes sessions for a removed device without affecting another device', function (): void {
    $first = $this->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password', 'serialnumber' => 'FIRST',
    ])->assertOk();
    $second = $this->postJson('/api/v1/auth/login', [
        'username' => 'mobile.user', 'password' => 'local-test-password', 'serialnumber' => 'SECOND',
    ])->assertOk();
    StorageUserDevice::where('serialnumber', 'FIRST')->firstOrFail()->delete();
    $this->withToken($first->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])->assertUnauthorized();
    $this->withToken($second->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
});

it('rejects a different configured local client and cookie-only authentication', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    config()->set('local_auth.client_id', '999');
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->flushHeaders()->withCookie('laravel_token', 'not-a-bearer')->getJson('/api/v1/catalogs/products')->assertUnauthorized();
});

it('does not expose the local OAuth client through global Passport token endpoints', function (string $path, string $grant): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    Passport::$clientUuids = false;
    try {
        $response = $this->postJson($path, [
            'client_id' => (string) config('local_auth.client_id'),
            'client_secret' => hash('sha256', config('local_auth.token_app')),
            'grant_type' => $grant,
            'refresh_token' => $login->json('data.auth.refreshToken'),
        ]);
        expect($response->getStatusCode())->toBeIn([400, 401]);
        expect(DB::table('oauth_access_tokens')->count())->toBe(1);
    } finally {
        Passport::$clientUuids = true;
    }
})->with([
    ['oauth/token', 'refresh_token'],
    ['oauth/token', 'client_credentials'],
    ['oauth/device/code', 'urn:ietf:params:oauth:grant-type:device_code'],
]);

it('limits refreshes by validated family and keeps the previous token usable after throttling', function (): void {
    config()->set('local_auth.limits.refresh_family', 1);
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $renewed = $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $renewed->json('data.auth.refreshToken')])
        ->assertTooManyRequests()->assertHeader('Retry-After')->assertJsonPath('meta.service', 'refresh-user');
    $this->withToken($renewed->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
});

it('keeps login and refresh IP budgets independent and reports the correct service', function (): void {
    config()->set('local_auth.limits.login_ip', 1);
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->postJson('/api/v1/auth/login', ['username' => 'another.user', 'password' => 'bad'])
        ->assertTooManyRequests()->assertHeader('Retry-After');
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
});

it('does not count successful logins as credential failures', function (): void {
    for ($attempt = 0; $attempt < 6; $attempt++) {
        $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    }
});

it('records authentication and replay events without credentials', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    $records = DB::table('authentication_audit_logs')->get();
    expect($records)->toHaveCount(3);
    expect($records->last()->reason)->toBe('refresh_reuse:401');
    $serialized = $records->toJson();
    expect($serialized)->not->toContain('local-test-password', config('local_auth.token_app'),
        $login->json('data.auth.accessToken'), $login->json('data.auth.refreshToken'));
});

it('validates login inputs against the actual SQL Server column lengths', function (array $fields): void {
    $this->postJson('/api/v1/auth/login', $fields + [
        'username' => 'mobile.user', 'password' => 'local-test-password',
    ])->assertUnprocessable();
    expect(DB::table('oauth_access_tokens')->count())->toBe(0);
})->with([
    [['username' => str_repeat('a', 21)]],
    [['serialnumber' => str_repeat('a', 31)]],
]);

it('enforces absolute expiry in UTC rather than the server local timezone', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('token_metadata')->update(['session_expires_at' => now('UTC')->subSecond()->format('Y-m-d H:i:s')]);
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
});

it('caps new tokens at the remaining absolute session lifetime', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $deadline = now('UTC')->addSeconds(120);
    DB::table('token_metadata')->update(['session_expires_at' => $deadline->format('Y-m-d H:i:s')]);
    $renewed = $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
    expect($renewed->json('data.auth.expiresIn'))->toBeLessThanOrEqual(120)->toBeGreaterThan(0);
    $data = $this->app->make(LocalRefreshTokenDecoder::class)
        ->decode($renewed->json('data.auth.refreshToken'));
    expect($data['expire_time'])->toBeLessThanOrEqual($deadline->timestamp);
});

it('rolls back rotation when profile authorization fails without consuming the original refresh', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('systemcenterdistributions')->update(['statusid' => 'D']);
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertForbidden();
    expect(DB::table('oauth_refresh_tokens')->count())->toBe(1);
    expect(DB::table('oauth_refresh_tokens')->where('revoked', true)->count())->toBe(0);
    expect(DB::table('oauth_refresh_tokens')->whereNotNull('consumed_at')->count())->toBe(0);
    expect(DB::table('token_metadata')->count())->toBe(1);
    DB::table('systemcenterdistributions')->update(['statusid' => 'E']);
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
});

it('audits application-token rejections and IP throttling before the controller', function (): void {
    config()->set('local_auth.limits.login_ip', 1);
    $this->withHeader('X-App-Token', 'invalid')->postJson('/api/v1/auth/login', [])->assertUnauthorized();
    $this->postJson('/api/v1/auth/login', [])->assertTooManyRequests();
    expect(DB::table('authentication_audit_logs')->count())->toBe(2);
    expect(DB::table('authentication_audit_logs')->pluck('successful')->all())->toBe([0, 0]);
});

it('limits authenticated profile requests per user without consuming the refresh budget', function (): void {
    config()->set('local_auth.limits.profile_user', 1);
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->withToken($login->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
    $this->getJson('/api/v1/auth/me')->assertTooManyRequests()->assertHeader('Retry-After')
        ->assertJsonPath('meta.service', 'current-user');
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
});

it('adds and rolls back integrity indexes without creating tables or deleting records', function (): void {
    $migration = require database_path('migrations/2026_10_29_161000_add_local_authentication_integrity_indexes.php');
    $migration->up();
    expect(Schema::hasIndex('users', 'users_local_username_unique'))->toBeTrue();
    expect(Schema::hasIndex('securitydeviceforuser', 'securitydeviceforuser_user_serial_unique'))->toBeTrue();
    expect(DB::table('users')->count())->toBe(1);
    $migration->down();
    expect(Schema::hasIndex('users', 'users_local_username_unique'))->toBeFalse();
});

it('aborts integrity migrations on existing duplicates without altering data', function (): void {
    $this->localUser(['username' => 'mobile.user']);
    $migration = require database_path('migrations/2026_10_29_161000_add_local_authentication_integrity_indexes.php');
    expect(fn () => $migration->up())->toThrow(RuntimeException::class, 'Resolve duplicate');
    expect(DB::table('users')->count())->toBe(2);
    expect(Schema::hasIndex('users', 'users_local_username_unique'))->toBeFalse();
});

it('preserves local refresh metadata when legacy cleanup removes expired access metadata', function (): void {
    Schema::create('refresh_tokens', function (Blueprint $table): void {
        $table->id();
        $table->dateTime('expires_at');
    });
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('oauth_access_tokens')->update(['expires_at' => '2000-01-01 00:00:00']);
    $this->app->make(TokenManagementService::class)->cleanupExpiredTokens();
    expect(DB::table('token_metadata')->count())->toBe(1);
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertOk();
});

it('purges only local families past absolute expiry and retention', function (): void {
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $oldId = DB::table('token_metadata')->value('token_id');
    DB::table('token_metadata')->update(['session_expires_at' => now('UTC')->subDays(8)]);
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->artisan('auth:purge-local-sessions', ['--no-interaction' => true])->assertSuccessful();
    expect(DB::table('oauth_access_tokens')->where('id', $oldId)->exists())->toBeFalse();
    expect(DB::table('oauth_access_tokens')->count())->toBe(1);
    expect(DB::table('oauth_refresh_tokens')->count())->toBe(1);
    expect(DB::table('token_metadata')->count())->toBe(1);
});
