<?php

declare(strict_types=1);

use App\Http\Middleware\AuditLocalAuthentication;
use App\Http\Middleware\AuthenticateLocalApp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Middleware\PermissionMiddleware;

beforeEach(function (): void {
    $this->withoutMiddleware([AuditLocalAuthentication::class, AuthenticateLocalApp::class, PermissionMiddleware::class]);

    Schema::create('receiptasn', function (Blueprint $table): void {
        $table->id();
        $table->string('uuid_asn');
        $table->string('shipmentid');
    });
    Schema::create('storagelocations', function (Blueprint $table): void {
        $table->id();
    });
    DB::table('receiptasn')->insert([
        'uuid_asn' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'shipmentid' => 'VOUCHER-001',
    ]);
    DB::table('storagelocations')->insert(['id' => 1]);
});

it('returns the same validation envelope for both verification endpoints', function (
    string $endpoint, array $payload, array $fields, bool $acceptJson
): void {
    $response = $acceptJson
        ? $this->postJson('/api/v1/verification/'.$endpoint, $payload)
        : $this->post('/api/v1/verification/'.$endpoint, $payload);

    $response->assertUnprocessable()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 422)
        ->assertJsonPath('message', 'Errores de validación')
        ->assertJsonPath('meta.service', 'verification'.$endpoint)
        ->assertJsonMissingPath('meta.errors')
        ->assertJsonMissingPath('meta.errorCode')
        ->assertJsonStructure([
            'success', 'code', 'message',
            'meta' => ['version', 'service', 'processDate'],
            'data' => ['errors' => $fields],
        ]);

    expect(array_keys($response->json()))->toBe(['success', 'code', 'message', 'meta', 'data']);
})->with([
    'missing voucher' => ['search-voucher', [], ['VoucherId']],
    'invalid voucher type' => ['search-voucher', ['VoucherId' => ['VOUCHER-001']], ['VoucherId']],
    'long voucher' => ['search-voucher', ['VoucherId' => str_repeat('A', 31)], ['VoucherId']],
    'unknown voucher' => ['search-voucher', ['VoucherId' => 'UNKNOWN'], ['VoucherId']],
    'missing start fields' => ['start-voucher', [], ['AsnId', 'LocationId', 'Temperature']],
    'unknown receipt and location' => ['start-voucher', ['AsnId' => 'UNKNOWN', 'LocationId' => 999, 'Temperature' => 5], ['AsnId', 'LocationId']],
    'invalid temperature precision' => ['start-voucher', ['AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916', 'LocationId' => 1, 'Temperature' => 4.123], ['Temperature']],
    'temperature above range' => ['start-voucher', ['AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916', 'LocationId' => 1, 'Temperature' => 101], ['Temperature']],
    'temperature below range' => ['start-voucher', ['AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916', 'LocationId' => 1, 'Temperature' => -41], ['Temperature']],
])->with([true, false]);
