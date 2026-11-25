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
        return [
            'CenterId' => $this->centerid,
            'WarehouseId' => $this->warehouseid,
            'AsnId' => $this->uuid_asn,
            'VoucherId' => $this->shipmentid,
            'RouteName' => $this->provider->name ?? '',
            'RouteId' => $this->provider?->providerid,
            'OperatorName' => $this->operator?->name,
            'OperatorId' => $this->operator?->barcode,
            'StatusId' => $this->statusid,
            'StatusName' => $this->status->name ?? '',

            'Products' => ReceiptStockResource::collection($this->stockplan),
            'ExpectedLines' => $this->verification_summary ?? [],

            'AsnData' => [
                'Temperature' => $this->receiptdata?->temperature ?? '',
                'TemperatureProd' => $this->receiptdata?->temperatureprod ?? '',
                'Door' => $this->receiptdata?->location?->name ?? '',
                'StartTime' => $this->timereceipt->unloadstartdate ?? '',
            ],

        ];
    }
}
