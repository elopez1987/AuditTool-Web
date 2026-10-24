<?php

declare(strict_types=1);

namespace Tests;

use Auth0\Laravel\Guards\AuthorizationGuard;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use OpenSSLAsymmetricKey;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var array<string, array{private: OpenSSLAsymmetricKey, certificate: string}>
     */
    private static array $signingKeys = [];

    public function createApplication(): Application
    {
        $app = require dirname(__DIR__).'/bootstrap/app.php';
        $app->afterBootstrapping(LoadConfiguration::class, static function (Application $app): void {
            $app['config']->set([
                'app.env' => 'testing',
                'app.debug' => false,
                'database.default' => 'testing',
                'database.connections' => [
                    'testing' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
                ],
                'cache.default' => 'array',
                'session.driver' => 'array',
                'activitylog.enabled' => false,
                'mobile_auth' => [
                    'enabled' => true,
                    'domain' => 'mobile.auth0.example.test',
                    'audience' => 'https://audittool.example.test/api/mobile',
                    'allowed_client_ids' => ['flutter-native-client'],
                ],
            ]);
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function prepareMobileAuthentication(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('uuiduser')->nullable();
            $table->string('username')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('auth0')->nullable();
            $table->string('password')->nullable();
            $table->string('statusid')->default('E');
            $table->softDeletes();
        });
        foreach (['roles', 'permissions'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }
        foreach (['model_has_roles' => 'role_id', 'model_has_permissions' => 'permission_id'] as $name => $foreignKey) {
            Schema::create($name, function (Blueprint $table) use ($foreignKey): void {
                $table->unsignedBigInteger($foreignKey);
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
            });
        }
        Schema::create('role_has_permissions', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
        });
        Schema::create('securityaccesswarehouses', function (Blueprint $table): void {
            $table->id();
            $table->string('username');
            $table->string('centerid');
            $table->string('warehouseid');
        });

        $this->fakeJwks();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function localUser(array $attributes = []): int
    {
        return DB::table('users')->insertGetId(array_replace([
            'uuiduser' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
            'username' => 'mobile.user',
            'name' => 'Mobile User',
            'email' => 'mobile.user@example.test',
            'auth0' => 'auth0|mobile-user',
            'password' => 'not-an-authentication-source',
            'statusid' => 'E',
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    protected function accessToken(array $overrides = [], string $kid = 'test-key'): string
    {
        $claims = array_replace([
            'iss' => 'https://mobile.auth0.example.test/',
            'aud' => 'https://audittool.example.test/api/mobile',
            'sub' => 'auth0|mobile-user',
            'azp' => 'flutter-native-client',
            'scope' => 'openid profile email mobile:access',
            'iat' => time() - 10,
            'exp' => time() + 600,
        ], $overrides);
        $input = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT', 'kid' => $kid], JSON_THROW_ON_ERROR))
            .'.'.$this->base64Url(json_encode($claims, JSON_THROW_ON_ERROR));
        if (! openssl_sign($input, $signature, $this->signingKey($kid)['private'], OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Could not sign the test token.');
        }

        return $input.'.'.$this->base64Url($signature);
    }

    protected function jwksResponse(string $kid = 'test-key'): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'keys' => [[
                'kty' => 'RSA',
                'alg' => 'RS256',
                'use' => 'sig',
                'kid' => $kid,
                'x5c' => [$this->signingKey($kid)['certificate']],
            ]],
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * @param  list<Response|\Exception>  $responses
     */
    protected function fakeJwks(array $responses = []): MockHandler
    {
        $handler = new MockHandler($responses ?: [$this->jwksResponse()]);
        /** @var AuthorizationGuard $guard */
        $guard = $this->app['auth']->guard('auth0-mobile');
        $guard->sdk()->configuration()->setHttpClient(new Client([
            'handler' => HandlerStack::create($handler),
        ]));

        return $handler;
    }

    /**
     * @return array{private: OpenSSLAsymmetricKey, certificate: string}
     */
    private function signingKey(string $kid): array
    {
        if (! isset(self::$signingKeys[$kid])) {
            $key = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
            if ($key === false) {
                throw new RuntimeException('Could not generate the test key.');
            }
            $csr = openssl_csr_new(['commonName' => 'mobile.auth0.example.test'], $key);
            $certificate = $csr === false ? false : openssl_csr_sign($csr, null, $key, 1);
            if ($certificate === false || ! openssl_x509_export($certificate, $pem)) {
                throw new RuntimeException('Could not generate the test certificate.');
            }
            self::$signingKeys[$kid] = [
                'private' => $key,
                'certificate' => preg_replace('/-----[^-]+-----|\s+/', '', $pem),
            ];
        }

        return self::$signingKeys[$kid];
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
