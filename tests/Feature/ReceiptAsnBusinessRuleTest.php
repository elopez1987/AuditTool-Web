<?php

declare(strict_types=1);

use App\Exceptions\ApiBusinessRuleException;
use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Receipt\ReceiptAsn;
use App\Repositories\Receipt\ReceiptAsnRepository;
use App\Services\Receipt\ReceiptAsnService;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

it('rejects a receipt already in process without updating it', function (): void {
    $receipt = new ReceiptAsn(['statusid' => 'IP']);
    $repository = Mockery::mock(ReceiptAsnRepository::class);
    $repository->shouldReceive('findByUuid')->once()->with('ASN-ID')->andReturn($receipt);
    $repository->shouldNotReceive('updateStartLocationAsn');

    expect(fn () => (new ReceiptAsnService($repository))->updateStartAsn([
        'AsnId' => 'ASN-ID', 'Temperature' => 5, 'LocationId' => 1,
    ]))->toThrow(ApiBusinessRuleException::class, 'El recibo ya está en estatus en proceso.');
});

it('updates a receipt when its status allows starting the process', function (string $status): void {
    $receipt = new ReceiptAsn(['statusid' => $status]);
    $data = ['AsnId' => 'ASN-ID', 'Temperature' => 5, 'LocationId' => 1];
    $repository = Mockery::mock(ReceiptAsnRepository::class);
    $repository->shouldReceive('findByUuid')->once()->with('ASN-ID')->andReturn($receipt);
    $repository->shouldReceive('updateStartLocationAsn')->once()->with($data, $receipt);

    (new ReceiptAsnService($repository))->updateStartAsn($data);
})->with(['PG', 'PR']);

it('rejects a missing or inaccessible receipt without updating it', function (): void {
    $repository = Mockery::mock(ReceiptAsnRepository::class);
    $repository->shouldReceive('findByUuid')->once()->with('MISSING')->andReturnNull();
    $repository->shouldNotReceive('updateStartLocationAsn');

    expect(fn () => (new ReceiptAsnService($repository))->updateStartAsn([
        'AsnId' => 'MISSING', 'Temperature' => 5, 'LocationId' => 1,
    ]))->toThrow(ApiEntityNotFoundException::class, 'El recibo no existe o no está disponible para iniciar el proceso.');
});

it('returns the receipt business rule message using the modern API structure', function (): void {
    $repository = Mockery::mock(ReceiptAsnRepository::class);
    $repository->shouldReceive('findByUuid')
        ->once()->with('ASN-ID')->andReturn(new ReceiptAsn(['statusid' => 'IP']));
    $repository->shouldNotReceive('updateStartLocationAsn');
    $this->app->instance(ReceiptAsnRepository::class, $repository);
    Route::post('/api/test-receipt-start', function (Request $request, ReceiptAsnService $service): void {
        $service->updateStartAsn($request->all());
    })->name('test.receipt-start');

    $this->post('/api/test-receipt-start', ['AsnId' => 'ASN-ID', 'Temperature' => 5, 'LocationId' => 1])
        ->assertStatus(409)
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 409)
        ->assertJsonPath('message', 'El recibo ya está en estatus en proceso.')
        ->assertJsonPath('meta.service', 'receipt-start')
        ->assertJsonPath('data', null)
        ->assertJsonStructure(['success', 'code', 'message', 'meta' => ['version', 'service', 'processDate'], 'data']);
});

it('supports reusable business rule messages without requiring a JSON accept header', function (string $message): void {
    Route::get('/api/test-business-rule', function () use ($message): never {
        throw new ApiBusinessRuleException($message);
    });

    $this->get('/api/test-business-rule')
        ->assertStatus(409)
        ->assertJsonPath('message', $message)
        ->assertJsonPath('code', 409)
        ->assertJsonPath('meta.service', 'test-business-rule');

    expect(new ApiBusinessRuleException($message))->toBeInstanceOf(ShouldntReport::class);
})->with([
    'El recurso ya fue procesado.',
    'No se puede modificar un registro cerrado.',
]);
