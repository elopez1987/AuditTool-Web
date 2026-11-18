<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
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
                'cache.limiter' => 'array',
                'session.driver' => 'array',
                'activitylog.enabled' => false,
                'local_auth.client_id' => null,
                'passport.connection' => null,
            ]);
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function prepareAuthenticationSchema(): void
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
            'auth0' => null,
            'password' => 'not-an-authentication-source',
            'statusid' => 'E',
        ], $attributes));
    }
}
