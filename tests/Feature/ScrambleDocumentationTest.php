<?php

declare(strict_types=1);

use Dedoc\Scramble\Generator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('generates API contracts with the correct credentials responses and product path', function (): void {
    Schema::create('inventorypartnumbers', function (Blueprint $table): void {
        $table->id();
        $table->string('prtnum');
        $table->string('lngdsc');
    });

    $specification = ($this->app->make(Generator::class))();
    $paths = $specification['paths'];

    expect($specification['openapi'])->toStartWith('3.1.');
    expect($specification['components']['securitySchemes']['http']['scheme'])->toBe('bearer');
    expect($specification['security'])->toBe([['http' => []]]);
    expect($paths)->toHaveCount(7);
    expect(array_sum(array_map(count(...), $paths)))->toBe(10);

    foreach (['login', 'refresh'] as $action) {
        $operation = $paths['/v1/auth/'.$action]['post'];
        expect($operation['security'])->toBe([]);
        expect(array_keys($operation['responses']))->toContain(200, 401, 403, 422, 429, 503);
        expect($operation['responses'][422]['content']['application/json']['schema']['properties']['data']['properties'])
            ->toHaveKey('errors');
    }

    foreach (['login' => 'post', 'refresh' => 'post', 'me' => 'get'] as $action => $method) {
        $operation = $paths['/v1/auth/'.$action][$method];
        $header = collect($operation['parameters'])->firstWhere('name', 'X-App-Token');
        expect($header['in'])->toBe('header');
        expect($header['required'])->toBeTrue();
    }

    expect($paths['/v1/auth/me']['get']['security'] ?? $specification['security'])->toBe([['http' => []]]);
    expect($paths['/v1/catalogs/products']['get']['security'] ?? $specification['security'])->toBe([['http' => []]]);

    $product = $paths['/v1/catalogs/products/{product}']['get'];
    expect($product['operationId'])->toBe('getInventoryProductByPartNumber');
    expect($product['parameters'])->toHaveCount(1);
    expect($product['parameters'][0]['name'])->toBe('product');
    expect($product['parameters'][0]['schema']['type'])->toBe('string');
    expect($product['responses'][404]['content']['application/json']['schema']['properties']['data']['type'])->toBe('null');

    expect($paths['/v1/catalogs/products']['post']['summary'])->toContain('no implementado');
    expect($paths['/v1/catalogs/products/{product}']['put']['summary'])->toContain('no implementado');
    expect($paths['/v1/catalogs/products/{product}']['delete']['summary'])->toContain('no implementado');
    expect($specification['components']['schemas']['InventoryPartNumbersResource']['required'])
        ->toBe(['partnumberId', 'partnumberCode', 'description']);
});

it('restricts both documentation endpoints outside the local environment', function (): void {
    $this->get('/docs/api')->assertForbidden();
    $this->getJson('/docs/api.json')->assertForbidden();
});
