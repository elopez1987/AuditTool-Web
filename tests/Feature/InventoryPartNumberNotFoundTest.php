<?php

declare(strict_types=1);

use App\Exceptions\ApiEntityNotFoundException;
use App\Exceptions\CatalogEntityNotFoundException;
use App\Http\Controllers\Apis\Inventory\InventoryPartNumberController;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Repositories\Configuration\SecurityAccessWarehouseRepository;
use App\Repositories\Inventory\InventoryPartNumberRepository;
use App\Repositories\Security\AccessOriginRepository;
use App\Services\Inventory\InventoryPartNumberService;
use App\Services\Security\AccessOriginService;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;

it('throws an API exception when a product cannot be found', function (): void {
    $repository = Mockery::mock(InventoryPartNumberRepository::class);
    $repository->shouldReceive('findProductByPrtnum')->once()->with('MISSING')->andReturnNull();

    expect(fn () => (new InventoryPartNumberService($repository))->findProduct('MISSING'))
        ->toThrow(ApiEntityNotFoundException::class, 'El número de parte buscado no existe: MISSING');
});

it('returns the product when it exists', function (): void {
    $product = new InventoryPartnumber;
    $repository = Mockery::mock(InventoryPartNumberRepository::class);
    $repository->shouldReceive('findProductByPrtnum')->once()->with('SKU-001')->andReturn($product);

    expect((new InventoryPartNumberService($repository))->findProduct('SKU-001'))->toBe($product);
});

it('renders a standardized JSON 404 without requiring a JSON accept header', function (): void {
    $this->mock(InventoryPartNumberRepository::class, function (MockInterface $mock): void {
        $mock->shouldReceive('findProductByPrtnum')->once()->with('MISSING')->andReturnNull();
    });

    Route::get('/api/test-products/{prtnum}', [InventoryPartNumberController::class, 'show'])
        ->name('test.products');

    $this->get('/api/test-products/MISSING')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 404)
        ->assertJsonPath('message', 'El número de parte buscado no existe: MISSING')
        ->assertJsonPath('meta.service', 'products')
        ->assertJsonPath('data', null)
        ->assertJsonStructure(['success', 'code', 'message', 'meta' => ['version', 'service', 'processDate'], 'data']);
});

it('can render a reusable API not-found exception with a custom message', function (): void {
    Route::get('/api/test-missing-resource', function (): never {
        throw new ApiEntityNotFoundException('Recurso no encontrado.');
    });

    $this->getJson('/api/test-missing-resource')
        ->assertNotFound()
        ->assertJsonPath('message', 'Recurso no encontrado.')
        ->assertJsonPath('meta.service', 'test-missing-resource');
});

it('preserves the existing center access exception for the Blade flow', function (): void {
    $repository = Mockery::mock(AccessOriginRepository::class);
    $repository->shouldNotReceive('save');
    $warehouseRepository = Mockery::mock(SecurityAccessWarehouseRepository::class);
    $warehouseRepository->shouldReceive('getCenterIdByUsername')->once()->with('operator')->andReturnNull();

    expect(fn () => (new AccessOriginService($repository, $warehouseRepository))->store(['username' => 'operator']))
        ->toThrow(CatalogEntityNotFoundException::class, 'El usuario no cuenta con Centro de Distribución asignado.operator');
});
