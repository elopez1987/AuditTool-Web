<?php

declare(strict_types=1);

use App\Http\Resources\ReeceiptAsnResource;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptAsnData;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use Illuminate\Http\Request;

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
        ->setRelation('receiptdata', $receiptData);

    $result = (new ReeceiptAsnResource($receipt))->toArray(Request::create('/'));

    expect($result['AsnData'])->toBe([
        'Temperature' => $hasReceiptData ? 4 : '',
        'Door' => $hasReceiptData && $hasLocation ? 'Door 12' : '',
    ]);
})->with([
    'receipt data with location' => [true, true],
    'receipt data without location' => [true, false],
    'without receipt data' => [false, false],
]);
