<?php

declare(strict_types=1);

use App\Http\Resources\ReeceiptAsnResource;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnData;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Receipt\ReceiptStock;
use App\Models\Receipt\ReceiptStockCredit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

it('returns the location name through receipt data with optional relationships', function (bool $hasReceiptData, bool $hasLocation): void {
    $location = $hasLocation ? new StorageLocation(['name' => 'Door 12']) : null;
    $receiptData = $hasReceiptData
        ? (new ReceiptAsnData(['temperature' => 4]))->setRelation('location', $location)
        : null;
    $receipt = (new ReceiptAsn)
        ->setRelation('provider', new ReceiptProvider)
        ->setRelation('operator', new StorageOperator)
        ->setRelation('status', null)
        ->setRelation('stockplan', collect())
        ->setRelation('stockcredits', collect())
        ->setRelation('timereceipt', null)
        ->setRelation('receiptdata', $receiptData);

    $result = (new ReeceiptAsnResource($receipt))->toArray(Request::create('/'));

    expect($result['AsnData'])->toBe([
        'Temperature' => $hasReceiptData ? 4 : '',
        'TemperatureProd' => '',
        'Door' => $hasReceiptData && $hasLocation ? 'Door 12' : '',
        'StartTime' => '',
    ]);
})->with([
    'receipt data with location' => [true, true],
    'receipt data without location' => [true, false],
    'without receipt data' => [false, false],
]);

it('lists both expected product origins without collapsing matching product or line identifiers', function (bool $hasStock, bool $hasCredits): void {
    $product = new InventoryPartnumber([
        'prtnum' => '001005',
        'lngdsc' => 'Expected milk',
        'barcode' => '7501020526066',
    ]);
    $stock = (new ReceiptStock([
        'partnumberid' => 10,
        'rotary' => '4.0000',
        'definitive' => '8.0000',
    ]))->setAttribute('id', 7)->setRelation('partnumber', $product);
    $credit = (new ReceiptStockCredit([
        'inventorypartnumber_id' => 10,
        'rotary' => '0.0000',
        'definitive' => '0.0000',
    ]))->setAttribute('id', 7)->setRelation('inventorypartnumber', $product);
    $expectedLines = [['Source' => 'CreditNote', 'ExpectedLineId' => 7, 'ExpectedTotal' => '20.0000']];
    $receipt = (new ReceiptAsn)
        ->setAttribute('verification_summary', $expectedLines)
        ->setRelation('provider', null)
        ->setRelation('operator', null)
        ->setRelation('status', null)
        ->setRelation('receiptdata', null)
        ->setRelation('timereceipt', null)
        ->setRelation('stockplan', collect($hasStock ? [$stock] : []))
        ->setRelation('stockcredits', collect($hasCredits ? [$credit] : []));

    DB::enableQueryLog();
    DB::flushQueryLog();

    try {
        $result = (new ReeceiptAsnResource($receipt))->toArray(Request::create('/'));

        expect($result['ExpectedLines'])->toBe($expectedLines)
            ->and($result['Products'])->toHaveCount((int) $hasStock + (int) $hasCredits)
            ->and(DB::getQueryLog())->toBeEmpty();

        if ($hasStock) {
            expect($result['Products'][0])->toBe([
                'Source' => 'StockRoute',
                'ExpectedLineId' => 7,
                'PartNumberId' => 10,
                'PartNumberCode' => '001005',
                'Description' => 'Expected milk',
                'BarCode' => '7501020526066',
                'Rotary' => '4',
                'Definitive' => '8',
            ]);
        }

        if ($hasCredits) {
            expect($result['Products'][(int) $hasStock])->toBe([
                'Source' => 'CreditNote',
                'ExpectedLineId' => 7,
                'PartNumberId' => 10,
                'PartNumberCode' => '001005',
                'Description' => 'Expected milk',
                'BarCode' => '7501020526066',
                'Rotary' => '0.0000',
                'Definitive' => '0.0000',
            ]);
        }
    } finally {
        DB::disableQueryLog();
    }
})->with([
    'stock and credit' => [true, true],
    'stock only' => [true, false],
    'credit only' => [false, true],
    'no expected products' => [false, false],
]);
