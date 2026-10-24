<?php

declare(strict_types=1);

use App\Repositories\Security\LocalPassportClientRepository;
use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    $this->prepareMobileAuthentication();
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
    });
    Schema::table('securityaccesswarehouses', function (Blueprint $table): void {
        $table->string('defaultlevel')->nullable();
    });
    Schema::create('systemcenterdistributions', function (Blueprint $table): void {
        $table->string('centerid')->primary();
        $table->string('name');
        $table->string('statusid')->default('E');
        $table->text('auxiliary05')->nullable();
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
        ['centerid' => 'BRIDCUE', 'name' => 'BRIDGESTONE CUERNAVACA', 'auxiliary05' => json_encode(['APP-03', 'APP-04'])],
        ['centerid' => 'OTHER', 'name' => 'OTHER CENTER', 'auxiliary05' => null],
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
    DB::table('systemcenterdistributions')->update(['auxiliary05' => null]);
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
        ->assertJsonPath('data.auth.tokenType', 'Bearer')->assertJsonPath('data.auth.expiresIn', 86400)
        ->assertJsonPath('data.parameters.app', ['APP-03', 'APP-04'])
        ->assertJsonPath('data.parameters.user', ['USR-01'])
        ->assertJsonPath('data.parameters.warehouse', ['MX10APT' => ['SWH-01', 'SWH-02']])
        ->assertJsonPath('data.device', ['registered' => false, 'is_new' => false, 'warning' => 'No se proporcionó número de serie del dispositivo'])
        ->assertHeader('Cache-Control', 'no-store, private')->assertHeaderMissing('Set-Cookie');
    $token = (new Parser(new JoseEncoder))->parse($response->json('data.auth.accessToken'));
    expect($token->claims()->get('exp')->getTimestamp() - $token->claims()->get('iat')->getTimestamp())->toBe(86400);
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

it('rotates Passport refresh tokens and revokes only the associated access token', function (): void {
    $first = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $second = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $renewed = $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])
        ->assertOk()->assertJsonPath('meta.service', 'refresh-user');
    expect($renewed->json('data.auth.refreshToken'))->not->toBe($first->json('data.auth.refreshToken'));
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $first->json('data.auth.refreshToken')])->assertUnauthorized();
    $this->withToken($first->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->withToken($second->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
    $this->withToken($renewed->json('data.auth.accessToken'))->getJson('/api/v1/auth/me')->assertOk();
});

it('rejects refresh tokens after local account deactivation without issuing new tokens', function (): void {
    $login = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    DB::table('users')->update(['statusid' => 'D']);
    $this->postJson('/api/v1/auth/refresh', ['refreshToken' => $login->json('data.auth.refreshToken')])->assertUnauthorized();
    expect(DB::table('oauth_access_tokens')->count())->toBe(1);
});

it('keeps Auth0 PKCE and local Passport tokens isolated in both directions', function (): void {
    $id = DB::table('users')->value('id');
    DB::table('users')->where('id', $id)->update(['auth0' => 'auth0|mobile-user']);
    $local = $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'local-test-password'])->assertOk();
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')->assertOk();
    $this->withToken($this->accessToken())->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->withToken($local->json('data.auth.accessToken'))->getJson('/api/mobile/auth/me')->assertUnauthorized();
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
    expect(config('auth.guards.auth0-mobile.driver'))->toBe('auth0-mobile');
});

it('limits repeated credential attempts independently of the PKCE limiter', function (): void {
    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'incorrect'])->assertUnauthorized();
    }
    $this->postJson('/api/v1/auth/login', ['username' => 'mobile.user', 'password' => 'incorrect'])
        ->assertTooManyRequests()->assertJsonPath('code', 429);
    DB::table('users')->update(['auth0' => 'auth0|mobile-user']);
    $this->withToken($this->accessToken())->getJson('/api/mobile/auth/me')->assertOk();
});
