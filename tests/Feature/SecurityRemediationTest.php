<?php

declare(strict_types=1);

use App\Console\Commands\Yard\MensajeDeCapacidadDePatio;
use App\Http\Controllers\Configuration\CommandJobs\CommandController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\Web\Configuration\EdiToken\UpdateEdiTokenRequest;
use App\Http\Requests\Web\Configuration\TablesDataBase\UpdateSystemTableRequest;
use App\Http\Requests\Web\Security\Roles\UpdateRoleRequest;
use App\Models\Configuration\EDI\EdiToken;
use App\Traits\Web\General\EvidencePhotoTrait;
use App\Traits\Web\General\ValidatesImageUploads;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\SDK\Configuration\SdkConfiguration;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

final class MaintenanceTestUser extends GenericUser implements Authorizable
{
    use Illuminate\Foundation\Auth\Access\Authorizable;

    public function hasAnyRole(mixed $roles): bool
    {
        return (bool) $this->admin;
    }

    public function hasRole(mixed $role): bool
    {
        return (bool) $this->admin;
    }
}

function authenticateMaintenanceUser(bool $admin = true): void
{
    Auth::guard('auth0-session')->setCredential(CredentialEntity::create(
        user: new MaintenanceTestUser(['id' => 1, 'name' => 'Maintenance Test', 'admin' => $admin, 'access' => null]),
    ));
}

beforeEach(function (): void {
    config()->set([
        'auth0.guards.default' => [
            'domain' => 'maintenance-test.auth0.com',
            'clientId' => 'maintenance-test-client',
            'clientSecret' => bin2hex(random_bytes(32)),
        ],
        'auth0.guards.web' => [
            'strategy' => SdkConfiguration::STRATEGY_REGULAR,
            'cookieSecret' => bin2hex(random_bytes(32)),
            'redirectUri' => 'https://application.example.test/callback',
        ],
    ]);
});

it('registers command mutations only as protected POST routes', function (): void {
    $routes = collect(Route::getRoutes())->filter(
        static fn ($route): bool => $route->getControllerClass() === CommandController::class
            && $route->getActionMethod() !== 'commandmenu'
    );

    expect($routes)->toHaveCount(19);
    foreach ($routes as $route) {
        expect($route->methods())->toBe(['POST']);
        expect($route->gatherMiddleware())->toContain('auth:auth0-session', 'role:ADMINISTRADOR',
            'can:system.commands.'.$route->getName());
        expect(app('router')->gatherRouteMiddleware($route))->toContain(VerifyCsrfToken::class);
    }
    expect(Route::getRoutes()->getByName('clearsessions')->methods())->toBe(['POST']);
});

it('rejects GET and HEAD for every command mutation without running commands', function (): void {
    Artisan::shouldReceive('call')->never();
    $routes = collect(Route::getRoutes())->filter(
        static fn ($route): bool => $route->getControllerClass() === CommandController::class
            && $route->getActionMethod() !== 'commandmenu'
    );
    foreach ($routes as $route) {
        $this->getJson('/'.$route->uri())->assertStatus(405);
        $this->head('/'.$route->uri())->assertStatus(405);
    }
});

it('denies anonymous integration calls without running commands', function (string $uri): void {
    Artisan::shouldReceive('call')->never();
    $this->postJson('/'.$uri)->assertUnauthorized();
})->with(['senddatatoizel', 'senddatatorcontrol', 'deleteusers', 'clearJobs', 'clear-failed-jobs', 'clearsessions']);

it('denies non-administrators without running commands', function (): void {
    authenticateMaintenanceUser(false);
    Artisan::shouldReceive('call')->never();
    $this->postJson('/senddatatoizel')->assertForbidden();
});

it('allows an administrator integration action with commands mocked', function (): void {
    authenticateMaintenanceUser();
    Artisan::shouldReceive('call')->once()->with('izel:send-data-izel-wms-v5')->andReturn(0);
    $this->post('/senddatatoizel')->assertRedirect();
});

it('requires a real CSRF token on maintenance POST requests', function (): void {
    authenticateMaintenanceUser();
    Artisan::shouldReceive('call')->never();
    $this->app->bind(VerifyCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends VerifyCsrfToken
    {
        protected function runningUnitTests(): bool
        {
            return false;
        }
    });
    $this->postJson('/senddatatoizel')->assertStatus(419);
});

it('renders maintenance forms with CSRF and confirmation hooks', function (): void {
    $html = Blade::render('<x-maintenance-action :action="$action">Ejecutar</x-maintenance-action>', [
        'action' => '/senddatatoizel',
    ]);
    expect($html)->toContain('method="POST"', 'name="_token"', 'data-maintenance-action', 'type="submit"');
});

it('does not serialize or activity-log EDI credentials', function (): void {
    $token = new EdiToken(['client_secret' => bin2hex(random_bytes(32)), 'client_id' => 'test-client']);
    expect($token->toArray())->not->toHaveKey('client_secret');
    expect($token->getActivitylogOptions()->logExceptAttributes)->toContain('client_secret');
});

it('never prefills an existing EDI credential in the edit form', function (): void {
    $secret = bin2hex(random_bytes(32));
    $html = view('Configuration.EdiToken.partials.formEdit', [
        'token' => new EdiToken(['client_secret' => $secret]),
    ])->render();
    expect($html)->not->toContain($secret);
    expect($html)->toContain('type="password"', 'autocomplete="new-password"');
});

it('preserves an existing EDI credential when the replacement is blank', function (): void {
    authenticateMaintenanceUser();
    Route::post('/remediation-test/edi', fn (UpdateEdiTokenRequest $request) => $request->validated())
        ->middleware('web');
    $this->postJson('/remediation-test/edi', [
        'baseuri' => 'https://integration.example.test',
        'client_id' => 'test-client',
        'client_secret' => '',
        'timeout' => 10,
        'tokentype' => 'Bearer',
        'servicetype' => 'test',
        'audience' => 'test-audience',
        'id' => 999,
    ])->assertOk()->assertJsonMissingPath('client_secret')->assertJsonMissingPath('id')
        ->assertJsonPath('audience', 'test-audience');
});

it('does not allow schema metadata writes to change the table identifier', function (): void {
    authenticateMaintenanceUser();
    Route::post('/remediation-test/table', fn (UpdateSystemTableRequest $request) => $request->validated())
        ->middleware('web');
    $this->postJson('/remediation-test/table', [
        'description' => 'Safe description', 'notes' => 'Safe notes', 'tableid' => 'users',
    ])->assertOk()->assertJsonMissingPath('tableid')->assertJsonPath('description', 'Safe description');
});

it('fails closed when the yard messaging credential is missing', function (): void {
    config()->set('services.whatsapp_yard.token', null);
    expect(fn () => (new MensajeDeCapacidadDePatio)->getBodyData(1, 2, 50, (object) ['phonenumber' => '000']))
        ->toThrow(RuntimeException::class, 'The yard messaging credential is not configured.');
});

it('uses the configured yard credential instead of an embedded literal', function (): void {
    $secret = bin2hex(random_bytes(32));
    config()->set('services.whatsapp_yard.token', $secret);
    $data = (new MensajeDeCapacidadDePatio)->getBodyData(1, 2, 50, (object) ['phonenumber' => '000']);
    expect(hash_equals($secret, $data['token']))->toBeTrue();
});

it('removes executable vendor demos from the public directory', function (): void {
    expect(is_file(public_path('theme/assets/libs/jquery.repeater/test-post-parse.php')))->toBeFalse();
});

it('validates permission updates and ignores unexpected privilege fields', function (): void {
    authenticateMaintenanceUser();
    $this->prepareAuthenticationSchema();
    Schema::table('permissions', function (Blueprint $table): void {
        $table->integer('moduleid')->nullable();
        $table->integer('functionid')->nullable();
        $table->integer('groupid')->nullable();
        $table->string('description')->nullable();
    });
    Schema::create('securitymodules', function (Blueprint $table): void {
        $table->integer('moduleid')->primary();
    });
    Schema::create('securityfunctions', function (Blueprint $table): void {
        $table->integer('functionid')->primary();
        $table->integer('moduleid');
    });
    DB::table('securitymodules')->insert(['moduleid' => 1]);
    DB::table('securityfunctions')->insert(['functionid' => 2, 'moduleid' => 1]);
    DB::table('permissions')->insert([
        'id' => 7, 'name' => 'catalog.index', 'guard_name' => 'web', 'groupid' => 1,
        'moduleid' => 1, 'functionid' => 2, 'description' => 'Original',
    ]);
    $this->put('/securitypermissions/7', [
        'name' => 'catalog.index', 'moduleid' => 1, 'functionid' => 2, 'description' => 'Updated',
        'guard_name' => 'api', 'groupid' => 999,
    ])->assertRedirect();
    $this->assertDatabaseHas('permissions', [
        'id' => 7, 'description' => 'Updated', 'guard_name' => 'web', 'groupid' => 1,
    ]);
    $this->putJson('/securitypermissions/7', [
        'name' => '<script>', 'moduleid' => 1, 'functionid' => 2, 'description' => 'Rejected',
    ])->assertUnprocessable()->assertJsonValidationErrors('name');
    $this->assertDatabaseHas('permissions', ['id' => 7, 'description' => 'Updated']);
});

it('generates PNG names without retaining the client filename', function (): void {
    $validator = new class
    {
        use ValidatesImageUploads {
            validatedImageName as public;
            validateImagePathSegments as public;
        }
    };
    $name = $validator->validatedImageName(UploadedFile::fake()->image('client-name.png', 16, 16));
    expect($name)->toMatch('/^[a-f0-9-]{36}\.png$/')->not->toContain('client-name');
    expect(fn () => $validator->validateImagePathSegments(['../outside']))
        ->toThrow(ValidationException::class);
});

it('rejects executable content, oversized images and excessive dimensions', function (): void {
    $validator = new class
    {
        use ValidatesImageUploads {
            validatedImageName as public;
        }
    };
    foreach ([
        UploadedFile::fake()->createWithContent('payload.png', '<?php print("not an image");'),
        UploadedFile::fake()->image('large.png', 16, 16)->size(6000),
        UploadedFile::fake()->image('wide.png', 4097, 1),
    ] as $file) {
        expect(fn () => $validator->validatedImageName($file))->toThrow(ValidationException::class);
    }
});

it('rejects malformed base64 image content before writing files', function (): void {
    $photos = new class
    {
        use EvidencePhotoTrait;
    };
    expect(fn () => $photos->saveFileToModelBase64('not valid base64!', 'test', (object) [
        'id' => 1, 'centerid' => 'CENTER',
    ], 'images', 'test', 'other'))->toThrow(ValidationException::class);
});

it('serializes datatable configuration without allowing script termination', function (): void {
    $html = Blade::render('<x-datatable table-id="safe" ajax-url="/test" :columns="$columns" />', [
        'columns' => [['title' => '</script><script>alert(1)</script>', 'data' => 'name']],
    ]);
    expect($html)->not->toContain('</script><script>alert(1)</script>');
    expect($html)->toContain('JSON.parse');
});

it('does not mass assign role identity, guard or center from metadata updates', function (): void {
    authenticateMaintenanceUser();
    Schema::create('securitymenustructure', function (Blueprint $table): void {
        $table->string('menustrucid')->primary();
    });
    DB::table('securitymenustructure')->insert(['menustrucid' => 'MENU']);
    Route::post('/remediation-test/role', fn (UpdateRoleRequest $request) => $request->validated())
        ->middleware('web');
    $this->postJson('/remediation-test/role', [
        'description' => 'Updated', 'menustrucid' => 'MENU', 'statusid' => 'E',
        'name' => 'ADMINISTRADOR', 'guard_name' => 'api', 'centerid' => 'OTHER',
    ])->assertOk()->assertJsonMissingPath('name')->assertJsonMissingPath('guard_name')
        ->assertJsonMissingPath('centerid')->assertJsonPath('description', 'Updated');
});

it('denies sensitive requests before validation when the edit permission is missing', function (): void {
    authenticateMaintenanceUser(false);
    Route::post('/remediation-test/table', fn (UpdateSystemTableRequest $request) => $request->validated())
        ->middleware('web');
    $this->postJson('/remediation-test/table', ['description' => 'Rejected'])->assertForbidden();
});
