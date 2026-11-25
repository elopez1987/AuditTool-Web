<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Receipt\ReceiptStock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReceiptStock
 */
class ReceiptStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Source' => 'StockRoute',
            'ExpectedLineId' => $this->id,
            'PartNumberId' => $this->partnumberid,
            'PartNumberCode' => $this->partnumber->prtnum,
            'Description' => $this->partnumber->lngdsc,
            'BarCode' => $this->partnumber->barcode,
            'Rotary' => $this->rotary,
            'Definitive' => $this->definitive,
        ];
    }
}
