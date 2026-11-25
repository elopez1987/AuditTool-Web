<?php

declare(strict_types=1);

use App\Exceptions\ApiBusinessRuleException;
use App\Http\Middleware\AuditLocalAuthentication;
use App\Http\Middleware\AuthenticateLocalApp;
use App\Models\Receipt\ReceiptVerification;
use App\Services\Receipt\ExpectedReceiptMutationService;
use App\Services\Receipt\ReceiptExpectedProductsService;
use App\Services\Receipt\VerificationService;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;

beforeEach(function (): void {
    DB::statement('PRAGMA foreign_keys = ON');
    $this->withoutMiddleware([AuditLocalAuthentication::class, AuthenticateLocalApp::class, PermissionMiddleware::class]);
    $this->prepareAuthenticationSchema();

    $userId = $this->localUser(['username' => 'verification.operator']);
    $this->actingAs(User::findOrFail($userId), 'web');

    Schema::create('receiptasn', function (Blueprint $table): void {
        $table->id();
        $table->uuid('uuid_asn');
        $table->string('centerid');
        $table->string('warehouseid');
        $table->string('statusid');
        $table->timestamps();
    });
    Schema::create('inventoryunitsmeasure', function (Blueprint $table): void {
        $table->id();
        $table->string('unitmeasureid');
        $table->string('centerid')->default('CENTER');
        $table->string('warehouseid')->default('WAREHOUSE');
    });
    DB::table('inventoryunitsmeasure')->insert(['id' => 1, 'unitmeasureid' => 'PZ']);
    Schema::create('inventorypartnumbers', function (Blueprint $table): void {
        $table->id();
        $table->string('prtnum');
        $table->string('lngdsc')->nullable();
        $table->string('barcode')->nullable();
        $table->string('centerid')->default('CENTER');
        $table->string('warehouseid')->default('WAREHOUSE');
        $table->string('stkuom')->default('PZ');
    });
    Schema::create('systemstatus', function (Blueprint $table): void {
        $table->string('statusid')->primary();
    });
    Schema::create('receiptstock', function (Blueprint $table): void {
        $table->id();
        $table->unsignedBigInteger('receiptid');
        $table->unsignedBigInteger('partnumberid');
        $table->decimal('rotary', 18, 4);
        $table->decimal('definitive', 18, 4);
        $table->decimal('quantity', 18, 4)->nullable();
        $table->unsignedBigInteger('unitmeasureid')->nullable();
        $table->string('expirecode', 50)->nullable();
        foreach (['invoice', 'selled', 'creditnote', 'gifts', 'loaded'] as $column) {
            $table->decimal($column, 18, 4)->nullable();
        }
        $table->timestamps();
    });
    Schema::create('receiptasntimes', function (Blueprint $table): void {
        $table->id();
        $table->unsignedBigInteger('receiptid');
        $table->dateTime('unloadfinishdate')->nullable();
    });
    Schema::create('storagecomments', function (Blueprint $table): void {
        $table->id();
        $table->string('modeltype');
        $table->unsignedBigInteger('modelid');
        $table->unsignedBigInteger('createuser');
        $table->string('comment');
        $table->string('auxiliary01')->nullable();
        $table->timestamps();
    });
    (require database_path('migrations/2026_11_10_160934_create_receipt_verifications_table.php'))->up();
    (require database_path('migrations/2026_11_17_162645_create_receipt_stock_credits_table.php'))->up();
    (require database_path('migrations/2026_11_25_105202_add_expected_line_references_to_receipt_verifications.php'))->up();

    DB::table('securityaccesswarehouses')->insert([
        'username' => 'verification.operator',
        'centerid' => 'CENTER',
        'warehouseid' => 'WAREHOUSE',
    ]);
    DB::table('receiptasn')->insert([
        'id' => 1,
        'uuid_asn' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'centerid' => 'CENTER',
        'warehouseid' => 'WAREHOUSE',
        'statusid' => 'IP',
    ]);
    DB::table('inventorypartnumbers')->insert(['id' => 1, 'prtnum' => 'SKU-001']);
    DB::table('systemstatus')->insert([
        ['statusid' => 'TRP-01'],
        ['statusid' => 'TRP-02'],
    ]);
    DB::table('receiptstock')->insert([
        'receiptid' => 1,
        'partnumberid' => 1,
        'rotary' => 6,
        'definitive' => 4,
        'quantity' => 10,
        'unitmeasureid' => 1,
    ]);
});

it('creates a verification when its accumulated quantity reaches the available stock', function (): void {
    ReceiptVerification::create([
        'receiptasn_id' => 1,
        'inventorypartnumber_id' => 1,
        'quantity' => 7,
        'type_id' => 'TRP-01',
        'receiptstock_id' => 1,
        'inventoryunitsmeasure_id' => 1,
        'created_by' => auth()->id(),
    ]);

    $this->postJson('/api/v1/verification/verification', verificationPayload(3))
        ->assertCreated()
        ->assertJsonPath('success', true);

    expect(ReceiptVerification::query()
        ->where('receiptasn_id', 1)
        ->where('inventorypartnumber_id', 1)
        ->where('type_id', 'TRP-01')
        ->sum('quantity'))
        ->toBe(10);
});

it('rejects a verification when its accumulated quantity exceeds the available stock', function (): void {
    ReceiptVerification::create([
        'receiptasn_id' => 1,
        'inventorypartnumber_id' => 1,
        'quantity' => 8,
        'type_id' => 'TRP-01',
        'receiptstock_id' => 1,
        'inventoryunitsmeasure_id' => 1,
        'created_by' => auth()->id(),
    ]);

    $this->postJson('/api/v1/verification/verification', verificationPayload(3))
        ->assertUnprocessable()
        ->assertJsonPath('success', false)
        ->assertJsonPath('code', 422)
        ->assertJsonPath('message', 'Errores de validación')
        ->assertJsonPath('data.errors.Quantity.0', 'La cantidad registrada excede la cantidad disponible del producto.');

    expect(ReceiptVerification::query()
        ->where('receiptasn_id', 1)
        ->where('inventorypartnumber_id', 1)
        ->where('type_id', 'TRP-01')
        ->sum('quantity'))
        ->toBe(8);
});

it('shares the expected limit across both verification types', function (): void {
    ReceiptVerification::create([
        'receiptasn_id' => 1,
        'inventorypartnumber_id' => 1,
        'quantity' => 10,
        'type_id' => 'TRP-02',
        'receiptstock_id' => 1,
        'inventoryunitsmeasure_id' => 1,
        'created_by' => auth()->id(),
    ]);

    $this->postJson('/api/v1/verification/verification', verificationPayload(10))
        ->assertUnprocessable();

    expect(ReceiptVerification::query()
        ->where('type_id', 'TRP-01')
        ->sum('quantity'))
        ->toBe(0);
});

it('allows complete bidirectional reclassification without changing expected quantities', function (string $type): void {
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload('10.0000'), 'TypeId' => $type,
    ])->assertCreated()
        ->assertJsonPath('data.balance.RemainingTotal', '0.0000')
        ->assertJsonPath('data.balance.VerifiedTotal', '10.0000');

    $this->assertDatabaseHas('receiptstock', ['id' => 1, 'rotary' => 6, 'definitive' => 4, 'quantity' => 10]);
})->with(['TRP-01', 'TRP-02']);

it('uses persisted quantity rather than the sum of the planned classifications', function (): void {
    DB::table('receiptstock')->update(['quantity' => 5]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(6))->assertUnprocessable();
    $this->postJson('/api/v1/verification/verification', verificationPayload(5))->assertCreated();
});

it('receives a credit-only product with a shared limit despite zero planned classifications', function (): void {
    DB::table('receiptstock')->delete();
    createVerificationCredit();
    $this->postJson('/api/v1/verification/verification', verificationPayload(12))
        ->assertCreated()->assertJsonPath('data.balance.Source', 'CreditNote');
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(8), 'TypeId' => 'TRP-02',
    ])->assertCreated()->assertJsonPath('data.balance.RemainingTotal', '0.0000');
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertUnprocessable();
    $this->assertDatabaseHas('receipt_stock_credits', ['id' => 1, 'quantity_missing' => 20]);
});

it('requires explicit origin for an ambiguous product and maintains independent balances', function (): void {
    createVerificationCredit();
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))
        ->assertUnprocessable()->assertJsonValidationErrors('ExpectedLineId', 'data.errors');
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(10), 'Source' => 'StockRoute', 'ExpectedLineId' => 1,
    ])->assertCreated()->assertJsonPath('data.balance.RemainingTotal', '0.0000');
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(20), 'Source' => 'CreditNote', 'ExpectedLineId' => 1,
    ])->assertCreated()->assertJsonPath('data.balance.RemainingTotal', '0.0000');
});

it('persists fractional kilograms with dates lot unit and authenticated user', function (): void {
    DB::table('inventoryunitsmeasure')->where('id', 1)->update(['unitmeasureid' => 'KG']);
    DB::table('receiptstock')->update(['quantity' => '6.5000']);
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload('3.5001'),
        'ProductionDate' => '2025-12-01', 'ExpireDate' => '2026-12-01', 'ExpireCode' => str_repeat('L', 100),
    ])->assertCreated()->assertJsonPath('data.balance.RemainingTotal', '2.9999');
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload('2.9999'), 'TypeId' => 'TRP-02',
    ])->assertCreated()->assertJsonPath('data.balance.RemainingTotal', '0.0000');

    $record = ReceiptVerification::query()->firstOrFail();
    expect($record->quantity)->toBe('3.5001')
        ->and($record->created_by)->toBe(auth()->id())
        ->and($record->inventoryunitsmeasure_id)->toBe(1)
        ->and($record->productiondate->format('Y-m-d'))->toBe('2025-12-01')
        ->and($record->expirecode)->toHaveLength(100);
});

it('rejects invalid quantities without writing captures', function (string $quantity): void {
    $this->postJson('/api/v1/verification/verification', verificationPayload($quantity))
        ->assertUnprocessable()->assertJsonValidationErrors('Quantity', 'data.errors');
    $this->assertDatabaseCount('receipt_verifications', 0);
})->with(['0', '-1', '0.5', '1.00001', '100000000000000', '1e3']);

it('refuses incomplete expected quantities and units without guessing defaults', function (string $column): void {
    DB::table('receiptstock')->update([$column => null]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))
        ->assertUnprocessable()->assertJsonValidationErrors('ExpectedLineId', 'data.errors');
})->with(['quantity', 'unitmeasureid']);

it('replays an identical operation after closure without a second insert', function (): void {
    $payload = [...verificationPayload(3), 'ClientOperationId' => 'b8d50b98-d09b-44a8-ab54-5a18449de916'];
    $first = $this->postJson('/api/v1/verification/verification', $payload)->assertCreated();
    DB::table('receiptasn')->update(['statusid' => 'PR']);
    $this->postJson('/api/v1/verification/verification', $payload)
        ->assertOk()->assertJsonPath('data.replayed', true)
        ->assertJsonPath('data.verification.id', $first->json('data.verification.id'))
        ->assertJsonPath('data.balance', $first->json('data.balance'));
    $this->assertDatabaseCount('receipt_verifications', 1);
});

it('rejects operation reuse with a changed payload', function (): void {
    $payload = [...verificationPayload(3), 'ClientOperationId' => 'b8d50b98-d09b-44a8-ab54-5a18449de916'];
    $this->postJson('/api/v1/verification/verification', $payload)->assertCreated();
    $this->postJson('/api/v1/verification/verification', [...$payload, 'TypeId' => 'TRP-02'])->assertConflict();
    $this->assertDatabaseCount('receipt_verifications', 1);
});

it('rejects new captures outside the in-process status', function (string $status): void {
    DB::table('receiptasn')->update(['statusid' => $status]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertConflict();
})->with(['PG', 'PR', 'CA', 'TR']);

it('rejects crossed center-warehouse access even when both individual codes are allowed', function (): void {
    DB::table('securityaccesswarehouses')->delete();
    DB::table('securityaccesswarehouses')->insert([
        ['username' => 'verification.operator', 'centerid' => 'CENTER', 'warehouseid' => 'OTHER'],
        ['username' => 'verification.operator', 'centerid' => 'OTHER', 'warehouseid' => 'WAREHOUSE'],
    ]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertNotFound();
});

it('rejects references to another receipt or product', function (): void {
    DB::table('receiptstock')->insert([
        'id' => 2, 'receiptid' => 999, 'partnumberid' => 1, 'quantity' => 10,
        'rotary' => 6, 'definitive' => 4, 'unitmeasureid' => 1,
    ]);
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(1), 'Source' => 'StockRoute', 'ExpectedLineId' => 2,
    ])->assertNotFound();
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(1), 'PartNumberCode' => 'OTHER', 'Source' => 'StockRoute', 'ExpectedLineId' => 1,
    ])->assertNotFound();
});

it('blocks capture when historical records have no linked expected line', function (): void {
    ReceiptVerification::query()->create([
        'receiptasn_id' => 1, 'inventorypartnumber_id' => 1, 'quantity' => 3,
        'type_id' => 'TRP-01', 'inventoryunitsmeasure_id' => 1, 'created_by' => auth()->id(),
    ]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertConflict();
});

it('requires explicit shortage confirmation before finishing and records its reason', function (): void {
    $this->postJson('/api/v1/verification/verification', verificationPayload(3))->assertCreated();
    $payload = ['AsnId' => verificationPayload(1)['AsnId']];
    $this->postJson('/api/v1/verification/finish-voucher', $payload)->assertUnprocessable();
    $this->assertDatabaseHas('receiptasn', ['id' => 1, 'statusid' => 'IP']);
    $this->postJson('/api/v1/verification/finish-voucher', [
        ...$payload, 'ConfirmShortages' => true, 'ShortageReason' => 'Physical delivery short by seven pieces.',
    ])->assertOk();
    $this->assertDatabaseHas('receiptasn', ['id' => 1, 'statusid' => 'PR']);
    $this->assertDatabaseHas('storagecomments', [
        'modelid' => 1, 'createuser' => auth()->id(),
        'auxiliary01' => 'VERIFICATION_SHORTAGE',
        'comment' => 'Physical delivery short by seven pieces.',
    ]);
});

it('finishes a completely reclassified receipt without false shortages', function (): void {
    $this->postJson('/api/v1/verification/verification', verificationPayload(10))->assertCreated();
    $this->postJson('/api/v1/verification/finish-voucher', ['AsnId' => verificationPayload(1)['AsnId']])->assertOk();
    $this->assertDatabaseCount('storagecomments', 0);
});

it('enforces composite line identity at database level', function (): void {
    DB::table('inventorypartnumbers')->insert(['id' => 2, 'prtnum' => 'OTHER']);
    expect(fn () => ReceiptVerification::query()->create([
        'receiptasn_id' => 1, 'inventorypartnumber_id' => 2, 'quantity' => 1, 'receiptstock_id' => 1,
        'type_id' => 'TRP-01', 'inventoryunitsmeasure_id' => 1, 'created_by' => auth()->id(),
    ]))->toThrow(QueryException::class);
});

it('imports both product origins idempotently before receiving and preserves external missing quantity', function (): void {
    DB::table('receiptasn')->update(['statusid' => 'PG']);
    $service = $this->app->make(ReceiptExpectedProductsService::class);
    $body = verificationExpectedBody();
    $service->import(verificationPayload(1)['AsnId'], 'verification.operator', $body);
    $service->import(verificationPayload(1)['AsnId'], 'verification.operator', $body);
    $this->assertDatabaseCount('receiptstock', 1);
    $this->assertDatabaseCount('receipt_stock_credits', 1);
    $this->assertDatabaseHas('receiptstock', ['quantity' => 12, 'rotary' => 4, 'definitive' => 8, 'loaded' => 24]);
    $this->assertDatabaseHas('receipt_stock_credits', ['quantity' => 20, 'quantity_missing' => 20]);

    DB::table('receiptasn')->update(['statusid' => 'IP']);
    expect(fn () => $service->import(verificationPayload(1)['AsnId'], 'verification.operator', $body))
        ->toThrow(ApiBusinessRuleException::class);
});

it('rejects an ambiguous expected import without partially updating either origin', function (): void {
    DB::table('receiptasn')->update(['statusid' => 'PG']);
    $body = verificationExpectedBody();
    $body['CreditNote']['Items']['Product'][0]['PartNumberId'] = 'MISSING';
    expect(fn () => $this->app->make(ReceiptExpectedProductsService::class)
        ->import(verificationPayload(1)['AsnId'], 'verification.operator', $body))
        ->toThrow(ValidationException::class);
    $this->assertDatabaseHas('receiptstock', ['id' => 1, 'quantity' => 10]);
    $this->assertDatabaseCount('receipt_stock_credits', 0);
});

it('exposes both origins with shared balances and flags unresolved historical data', function (): void {
    createVerificationCredit();
    $this->postJson('/api/v1/verification/verification', [
        ...verificationPayload(10), 'Source' => 'StockRoute', 'ExpectedLineId' => 1,
    ])->assertCreated();
    $summary = $this->app->make(VerificationService::class)->summary(1);
    expect($summary)->toHaveCount(2)
        ->and($summary[0]['RemainingTotal'])->toBe('0.0000')
        ->and($summary[0]['RotaryVariance'])->toBe('4.0000')
        ->and($summary[1]['RemainingTotal'])->toBe('20.0000');
});

it('includes stock and credit products in the search voucher response', function (): void {
    Schema::table('receiptasn', function (Blueprint $table): void {
        $table->string('shipmentid')->nullable();
    });
    Schema::create('receiptasndata', function (Blueprint $table): void {
        $table->id();
        $table->unsignedBigInteger('receiptid');
        $table->unsignedBigInteger('locationid')->nullable();
    });
    DB::table('receiptasn')->where('id', 1)->update(['shipmentid' => '1020']);
    createVerificationCredit();

    $this->postJson('/api/v1/verification/search-voucher', ['VoucherId' => '1020'])
        ->assertOk()
        ->assertJsonCount(2, 'data.Voucher.Products')
        ->assertJsonPath('data.Voucher.Products.0.Source', 'StockRoute')
        ->assertJsonPath('data.Voucher.Products.1.Source', 'CreditNote')
        ->assertJsonPath('data.Voucher.Products.1.ExpectedLineId', 1)
        ->assertJsonPath('data.Voucher.Products.1.PartNumberCode', 'SKU-001')
        ->assertJsonCount(2, 'data.Voucher.ExpectedLines');
});

it('keeps historical reconciliation read-only by default and applies only unambiguous links', function (): void {
    ReceiptVerification::query()->create([
        'receiptasn_id' => 1, 'inventorypartnumber_id' => 1, 'quantity' => 3,
        'type_id' => 'TRP-01', 'inventoryunitsmeasure_id' => 1, 'created_by' => auth()->id(),
    ]);
    $summary = $this->app->make(VerificationService::class)->summary(1);
    expect($summary[0]['RequiresReconciliation'])->toBeTrue()
        ->and($summary[0]['CanReceive'])->toBeFalse();
    $this->artisan('receipt:reconcile-verification-lines')->assertSuccessful();
    $this->assertDatabaseHas('receipt_verifications', ['id' => 1, 'receiptstock_id' => null]);
    $this->artisan('receipt:reconcile-verification-lines', ['--apply' => true])->assertSuccessful();
    $this->assertDatabaseHas('receipt_verifications', ['id' => 1, 'receiptstock_id' => 1, 'quantity' => 3]);
    $this->postJson('/api/v1/verification/verification', verificationPayload(7))->assertCreated();
});

it('does not guess a historical origin when the same product appears in both arrays', function (): void {
    createVerificationCredit();
    ReceiptVerification::query()->create([
        'receiptasn_id' => 1, 'inventorypartnumber_id' => 1, 'quantity' => 3,
        'type_id' => 'TRP-01', 'inventoryunitsmeasure_id' => 1, 'created_by' => auth()->id(),
    ]);
    $this->artisan('receipt:reconcile-verification-lines', ['--apply' => true])->assertFailed();
    $this->assertDatabaseHas('receipt_verifications', ['id' => 1, 'receiptstock_id' => null, 'receipt_stock_credit_id' => null]);
});

it('blocks expected stock edits and deletes once receiving has started', function (string $operation): void {
    $service = $this->app->make(ExpectedReceiptMutationService::class);
    expect(fn () => match ($operation) {
        'update' => $service->updateStock(1, 'verification.operator', ['quantity' => 100]),
        'delete' => $service->deleteStock(1, 'verification.operator'),
        'deleteAsn' => $service->deleteAsn(1, 'verification.operator'),
    })->toThrow(ApiBusinessRuleException::class);
    $this->assertDatabaseHas('receiptstock', ['id' => 1, 'quantity' => 10]);
})->with(['update', 'delete', 'deleteAsn']);

it('requires a bearer token when the mobile authentication middleware is enabled', function (): void {
    $this->withMiddleware(AuthenticateLocalApp::class);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertUnauthorized();
    $this->assertDatabaseCount('receipt_verifications', 0);
});

it('requires the verification permission when permission middleware is enabled', function (): void {
    DB::table('permissions')->insert(['name' => 'verification.saveverification', 'guard_name' => 'web']);
    $this->withMiddleware(PermissionMiddleware::class);
    $this->postJson('/api/v1/verification/verification', verificationPayload(1))->assertForbidden();
    $this->assertDatabaseCount('receipt_verifications', 0);
});

it('validates related source fields and optional dates consistently', function (array $changes, string $field): void {
    $this->postJson('/api/v1/verification/verification', [...verificationPayload(1), ...$changes])
        ->assertUnprocessable()->assertJsonValidationErrors($field, 'data.errors');
})->with([
    [['Source' => 'StockRoute'], 'ExpectedLineId'],
    [['ExpectedLineId' => 1], 'Source'],
    [['TypeId' => 'OTHER'], 'TypeId'],
    [['ClientOperationId' => 'not-a-uuid'], 'ClientOperationId'],
    [['ProductionDate' => '2999-12-01'], 'ProductionDate'],
    [['ProductionDate' => '2025-12-01', 'ExpireDate' => '2025-11-01'], 'ExpireDate'],
]);

/** @return array<string, mixed> */
function verificationExpectedBody(): array
{
    return [
        'StockRoute' => ['Items' => ['Product' => [[
            'PartNumberId' => 'SKU-001', 'Description' => 'Expected milk', 'BarCode' => '0012345',
            'QuantityRotary' => 4, 'QuantityDefinitive' => 8, 'QuantityInvoice' => 4,
            'QuantityCreditNote' => 4, 'QuantityGifts' => 4, 'QuantitySelled' => 8, 'QuantityLoaded' => 24, 'UoMBase' => 'PZ',
        ]]]],
        'CreditNote' => ['Items' => ['Product' => [[
            'PartNumberId' => 'SKU-001', 'Quantity' => 20,
            'QuantityRotary' => 0, 'QuantityDefinitive' => 0, 'QuantityMissing' => 20, 'UoMBase' => 'PZ',
        ]]]],
    ];
}

function createVerificationCredit(): void
{
    DB::table('receipt_stock_credits')->insert([
        'id' => 1, 'receiptasn_id' => 1, 'inventorypartnumber_id' => 1,
        'quantity' => 20, 'rotary' => 0, 'definitive' => 0,
        'inventoryunitsmeasure_id' => 1, 'quantity_missing' => 20,
    ]);
}

function verificationPayload(int|string $quantity): array
{
    return [
        'AsnId' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'PartNumberCode' => 'SKU-001',
        'Quantity' => $quantity,
        'TypeId' => 'TRP-01',
    ];
}
