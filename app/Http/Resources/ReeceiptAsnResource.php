<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Receipt\ReceiptAsn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReceiptAsn
 */
class ReeceiptAsnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $provider = $this->loadedRelation('provider');
        $operator = $this->loadedRelation('operator');
        $status = $this->loadedRelation('status');
        $receiptData = $this->loadedRelation('receiptdata');
        $location = $receiptData?->relationLoaded('location') ? $receiptData->getRelation('location') : null;
        $timeReceipt = $this->loadedRelation('timereceipt');

        return [
            'CenterId' => $this->centerid,
            'WarehouseId' => $this->warehouseid,
            'AsnId' => $this->uuid_asn,
            'VoucherId' => $this->shipmentid,
            'RouteName' => $provider?->name ?? '',
            'RouteId' => $provider?->providerid,
            'OperatorName' => $operator?->name,
            'OperatorId' => $operator?->barcode,
            'StatusId' => $this->statusid,
            'StatusName' => $status?->name ?? '',

            'Products' => array_merge(
                ReceiptStockResource::collection($this->stockplan)->resolve($request),
                ReceiptStockCreditResource::collection($this->stockcredits)->resolve($request),
            ),
            'ExpectedLines' => $this->verification_summary ?? [],

            'AsnData' => [
                'Temperature' => $receiptData?->temperature ?? '',
                'TemperatureProd' => $receiptData?->temperatureprod ?? '',
                'Door' => $location?->name ?? '',
                'StartTime' => $timeReceipt?->unloadstartdate ?? '',
                'EndTime' => $timeReceipt?->unloadfinishdate ?? '',
            ],

        ];
    }

    private function loadedRelation(string $relation): mixed
    {
        return $this->resource->relationLoaded($relation)
            ? $this->resource->getRelation($relation)
            : null;
    }
}
