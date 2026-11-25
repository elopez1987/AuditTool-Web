<?php

declare(strict_types=1);

use App\Http\Middleware\AuditLocalAuthentication;
use App\Http\Middleware\AuthenticateLocalApp;
use App\Models\Configuration\EDI\SystemEventWebService;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptAsnRepository;
use App\Repositories\Receipt\VerificationWebServiceAuditRepository;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Middleware\PermissionMiddleware;

beforeEach(function (): void {
    $this->withoutMiddleware([AuditLocalAuthentication::class, AuthenticateLocalApp::class, PermissionMiddleware::class]);
    $this->prepareAuthenticationSchema();
    $this->actingAs(User::findOrFail($this->localUser()), 'web');

    Schema::create('systemeventwebservice', function (Blueprint $table): void {
        $table->id();
        $table->string('centerid', 30)->nullable();
        $table->string('warehouseid', 30)->nullable();
        $table->string('reference', 40)->nullable();
        $table->string('servicename', 60)->nullable();
        $table->dateTime('processdate')->nullable();
        $table->string('code', 510)->nullable();
        $table->string('type', 100)->nullable();
        $table->string('message', 300)->nullable();
        $table->text('messagejson')->nullable();
        $table->text('messagejsonresponse')->nullable();
        foreach (['value01', 'value02', 'value03'] as $column) {
            $table->string($column, 60)->nullable();
        }
        foreach (['value04', 'value05'] as $column) {
            $table->string($column, 100)->nullable();
        }
        $table->timestamps();
    });
    Schema::create('receiptasn', function (Blueprint $table): void {
        $table->id();
        $table->string('uuid_asn');
        $table->string('shipmentid');
        $table->string('centerid');
        $table->string('warehouseid');
    });
    Schema::create('storagelocations', function (Blueprint $table): void {
        $table->id();
    });
    DB::table('receiptasn')->insert([
        'uuid_asn' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'shipmentid' => 'VOUCHER-001',
        'centerid' => 'CENTER',
        'warehouseid' => 'WAREHOUSE',
    ]);
    DB::table('storagelocations')->insert(['id' => 1]);
});

it('audits the final validation response on every verification endpoint', function (string $endpoint): void {
    $payload = ['ExtraField' => 'Preserve unvalidated input'];
    $response = $this->postJson('/api/v1/verification/'.$endpoint, $payload)->assertUnprocessable();

    $event = SystemEventWebService::query()->sole();
    expect($event->messagejson)->toBe($payload)
        ->and($event->messagejsonresponse)->toBe($response->json())
        ->and($event->code)->toBe('422')
        ->and($event->type)->toBe('ERROR')
        ->and($event->servicename)->toBe($endpoint)
        ->and($event->value01)->toBe('mobile.user')
        ->and($event->value02)->toBe('POST')
        ->and($event->value04)->toBe('api/v1/verification/'.$endpoint);
})->with(['verification', 'search-voucher', 'start-voucher', 'finish-voucher']);

it('audits successful requests with receipt context and the exact returned json', function (): void {
    $payload = [
        'AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'LocationId' => 1,
        'Temperature' => 4.5,
        'TemperatureProd' => 4.5,
    ];
    $receipt = ReceiptAsn::query()->sole();
    $receipt->setAttribute('statusid', 'PG');
    $repository = $this->mock(ReceiptAsnRepository::class);
    $repository->shouldReceive('findByUuid')->once()->with($payload['AsnId'])->andReturn($receipt);
    $repository->shouldReceive('updateStartLocationAsn')->once()->with($payload, $receipt);

    $response = $this->postJson('/api/v1/verification/start-voucher', $payload)->assertOk();

    $event = SystemEventWebService::query()->sole();
    expect($event->messagejson)->toBe($payload)
        ->and($event->messagejsonresponse)->toBe($response->json())
        ->and($event->code)->toBe('200')
        ->and($event->type)->toBe('SUCCESS')
        ->and($event->reference)->toBe($payload['AsnId'])
        ->and($event->centerid)->toBe('CENTER')
        ->and($event->warehouseid)->toBe('WAREHOUSE')
        ->and($event->processdate)->not->toBeNull();
});

it('resolves receipt context by voucher and records a missing voucher response', function (): void {
    $response = $this->postJson('/api/v1/verification/search-voucher', [
        'VoucherId' => 'UNKNOWN',
    ])->assertUnprocessable();

    $event = SystemEventWebService::query()->sole();
    expect($event->reference)->toBe('UNKNOWN')
        ->and($event->centerid)->toBeNull()
        ->and($event->warehouseid)->toBeNull()
        ->and($event->messagejsonresponse)->toBe($response->json());

    expect($this->app->make(VerificationWebServiceAuditRepository::class)->receiptContext(null, 'VOUCHER-001'))
        ->toBe(['centerid' => 'CENTER', 'warehouseid' => 'WAREHOUSE']);
});

it('audits authentication failures even before the controller or audit handle runs', function (): void {
    $this->withMiddleware(AuthenticateLocalApp::class);
    $response = $this->postJson('/api/v1/verification/verification', [])->assertUnauthorized();

    $event = SystemEventWebService::query()->sole();
    expect($event->code)->toBe('401')
        ->and($event->messagejsonresponse)->toBe($response->json());
});

it('audits business rule exceptions as the final error response', function (): void {
    $receipt = ReceiptAsn::query()->sole();
    $receipt->setAttribute('statusid', 'IP');
    $this->mock(ReceiptAsnRepository::class)
        ->shouldReceive('findByUuid')->once()->andReturn($receipt);

    $response = $this->postJson('/api/v1/verification/start-voucher', [
        'AsnId' => $receipt->uuid_asn,
        'LocationId' => 1,
        'Temperature' => 4.5,
        'TemperatureProd' => 4.5,
    ])->assertConflict();

    $event = SystemEventWebService::query()->sole();
    expect($event->code)->toBe('409')
        ->and($event->messagejsonresponse)->toBe($response->json());
});

it('audits permission denials before the controller executes', function (): void {
    DB::table('permissions')->insert(['name' => 'verification.search-voucher', 'guard_name' => 'web']);
    $this->withMiddleware(PermissionMiddleware::class);

    $response = $this->postJson('/api/v1/verification/search-voucher', [])->assertForbidden();

    $event = SystemEventWebService::query()->sole();
    expect($event->code)->toBe('403')
        ->and($event->messagejsonresponse)->toBe($response->json());
});

it('audits the final server error without exposing the original exception', function (): void {
    $this->mock(ReceiptAsnRepository::class)
        ->shouldReceive('findByUuid')->once()->andThrow(new RuntimeException('Internal failure details'));

    $response = $this->postJson('/api/v1/verification/start-voucher', [
        'AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'LocationId' => 1,
        'Temperature' => 4.5,
        'TemperatureProd' => 4.5,
    ])->assertStatus(500);

    $event = SystemEventWebService::query()->sole();
    expect($event->code)->toBe('500')
        ->and($event->messagejsonresponse)->toBe($response->json())
        ->and($event->getRawOriginal('messagejsonresponse'))->not->toContain('Internal failure details');
});

it('redacts credentials recursively without recording bearer headers', function (): void {
    $payload = [
        'password' => 'not-a-real-password',
        'nested' => ['access_token' => 'not-a-real-token', 'Quantity' => '1.0000'],
    ];
    $this->withHeader('Authorization', 'Bearer not-a-real-header')
        ->postJson('/api/v1/verification/search-voucher', $payload)->assertUnprocessable();

    $event = SystemEventWebService::query()->sole();
    expect($event->messagejson)->toBe([
        'password' => '[REDACTED]',
        'nested' => ['access_token' => '[REDACTED]', 'Quantity' => '1.0000'],
    ])->and($event->getRawOriginal('messagejson'))->not->toContain('not-a-real-');
});

it('preserves the original response if audit persistence fails', function (): void {
    Log::spy();
    Schema::drop('systemeventwebservice');

    $this->postJson('/api/v1/verification/search-voucher', [])->assertUnprocessable()
        ->assertJsonPath('message', 'Errores de validación');

    Log::shouldHaveReceived('error')->once()->with(
        'Verification web service audit persistence failed.',
        Mockery::on(fn (array $context): bool => $context['endpoint'] === 'search-voucher'
            && $context['status'] === 422
            && ! array_key_exists('message', $context)),
    );
});
