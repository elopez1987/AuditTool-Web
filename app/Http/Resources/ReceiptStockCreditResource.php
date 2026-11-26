<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Receipt\ReceiptStockCredit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReceiptStockCredit
 */
class ReceiptStockCreditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Source' => 'CreditNote',
            'ExpectedLineId' => $this->id,
            'PartNumberId' => $this->inventorypartnumber_id,
            'PartNumberCode' => $this->inventorypartnumber->prtnum,
            'Description' => $this->inventorypartnumber->lngdsc,
            'BarCode' => $this->inventorypartnumber->barcode,
            'Rotary' => $this->rotary,
            'Definitive' => $this->definitive,
        ];
    }
}
