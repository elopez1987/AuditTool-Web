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
    expect($paths)->toHaveCount(14);
    expect(array_sum(array_map(count(...), $paths)))->toBe(14);

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

    $catalogs = [
        'products' => ['listInventoryProducts', 'InventoryPartNumbersResource', ['PartnumberId', 'PartnumberCode', 'Description']],
        'locations' => ['listCatalogLocations', 'StorageLocationResource', ['Id', 'Name', 'LocationId', 'BarCode']],
        'operators' => ['listCatalogOperators', 'StorageOperatorResource', ['id', 'barcode', 'name']],
        'routes' => ['listCatalogRoutes', 'ReceiptProviderResource', ['CenterId', 'WarehouseId', 'Id', 'Name', 'RouteId']],
        'categories' => ['listCatalogCategories', 'CategoriesResource', ['CenterId', 'CategoryId', 'CategoryName']],
    ];

    foreach ($catalogs as $catalog => [$operationId, $resource, $fields]) {
        $path = $paths['/v1/catalogs/'.$catalog];
        expect(array_keys($path))->toBe(['get']);

        $operation = $path['get'];
        expect($operation['operationId'])->toBe($operationId);
        expect($operation['tags'])->toContain('Catálogos de inventario');
        expect($operation['security'] ?? $specification['security'])->toBe([['http' => []]]);
        expect(array_keys($operation['responses']))->toContain(200, 401, 403);

        $schema = $operation['responses'][200]['content']['application/json']['schema'];
        expect($schema['required'])->toBe(['success', 'code', 'message', 'meta', 'data']);
        expect($schema['properties']['data']['properties'][$catalog]['type'])->toBe('array');
        expect($schema['properties']['data']['properties'][$catalog]['items']['$ref'])
            ->toBe('#/components/schemas/'.$resource);
        expect($specification['components']['schemas'][$resource]['required'])->toBe($fields);

        foreach ([401, 403] as $status) {
            expect($operation['responses'][$status]['content']['application/json']['schema']['properties']['data']['type'])
                ->toBe('null');
        }
    }

    expect(array_keys($paths['/v1/catalogs/products/{product}']))->toBe(['get']);
    expect(array_keys($product['responses']))->toContain(200, 401, 403, 404);
    expect($product['parameters'][0]['example'])->toBe('SKU-001');
    expect($specification['components']['schemas']['InventoryPartNumbersResource']['required'])
        ->toBe(['PartnumberId', 'PartnumberCode', 'Description']);
    expect($specification['components']['schemas']['StorageLocationResource']['properties']['LocationId']['type'])
        ->toBe(['string', 'null']);
    expect($specification['components']['schemas']['StorageOperatorResource']['properties']['id']['type'])
        ->toBe('integer');

    foreach ([
        'search-voucher' => ['searchVerificationVoucher', ['VoucherId'], [200, 401, 403, 422]],
        'start-voucher' => ['startVerificationVoucher', ['AsnId', 'LocationId', 'Temperature'], [200, 401, 403, 404, 409, 422]],
        'finish-voucher' => ['finishVerificationVoucher', ['AsnId'], [200, 401, 403, 404, 409, 422]],
    ] as $action => [$operationId, $fields, $statuses]) {
        $path = $paths['/v1/verification/'.$action];
        expect(array_keys($path))->toBe(['post']);
        $operation = $path['post'];
        expect($operation['operationId'])->toBe($operationId);
        expect($operation['tags'])->toContain('Verificación de recibos');
        expect($operation['security'] ?? $specification['security'])->toBe([['http' => []]]);
        expect(array_keys($operation['responses']))->toContain(...$statuses);

        $body = $operation['requestBody']['content']['application/json']['schema'];
        if (isset($body['allOf'])) {
            $body = $body['allOf'][0];
        }
        if (isset($body['$ref'])) {
            $body = $specification['components']['schemas'][basename($body['$ref'])];
        }
        expect($body['required'])->toBe($fields);
        expect(array_keys($body['properties']))->toBe($fields);
        expect($operation['responses'][422]['content']['application/json']['schema']['properties']['data']['properties'])
            ->toHaveKey('errors');
    }

    $finish = $paths['/v1/verification/finish-voucher']['post'];
    $finishSuccess = $finish['responses'][200]['content']['application/json']['schema'];
    expect($finishSuccess['required'])->toBe(['success', 'code', 'message', 'meta', 'data']);
    expect($finishSuccess['properties']['data']['type'])->toBe('array');
    foreach ([401, 403, 404, 409] as $status) {
        expect($finish['responses'][$status]['content']['application/json']['schema']['properties']['data']['type'])
            ->toBe('null');
    }

    $voucher = $paths['/v1/verification/search-voucher']['post']['responses'][200]['content']['application/json']['schema'];
    expect($voucher['properties']['data']['properties']['Voucher']['$ref'])
        ->toBe('#/components/schemas/ReeceiptAsnResource');
    expect($specification['components']['schemas']['ReeceiptAsnResource']['required'])
        ->toBe(['CenterId', 'WarehouseId', 'AsnId', 'VoucherId', 'RouteName', 'RouteId', 'OperatorName', 'OperatorId', 'Products']);
    expect($specification['components']['schemas']['ReceiptStockResource']['required'])
        ->toBe(['PartNumberId', 'PartNumberCode', 'Description', 'BarCode', 'Rotary', 'Definitive']);
});

it('restricts both documentation endpoints outside the local environment', function (): void {
    $this->get('/docs/api')->assertForbidden();
    $this->getJson('/docs/api.json')->assertForbidden();
});
