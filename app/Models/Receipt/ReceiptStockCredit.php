<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptStockCredit extends Model
{
    protected $fillable = [
        'receiptasn_id',
        'inventorypartnumber_id',
        'quantity',
        'rotary',
        'definitive',
        'rotarycheck',
        'definitivecheck',
        'inventoryunitsmeasure_id',
    ];

    public function receiptasn(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptasn_id');
    }

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id');
    }

    public function inventoryunitsmeasure(): BelongsTo
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'inventoryunitsmeasure_id');
    }
}
