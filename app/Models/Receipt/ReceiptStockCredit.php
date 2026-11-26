<?php

declare(strict_types=1);

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReceiptStockCredit extends Model
{
    protected $fillable = [
        'receiptasn_id',
        'inventorypartnumber_id',
        'quantity',
        'rotary',
        'definitive',
        'quantity_missing',
        'inventoryunitsmeasure_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'rotary' => 'decimal:4',
            'definitive' => 'decimal:4',
            'quantity_missing' => 'decimal:4',
        ];
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(ReceiptVerification::class, 'receipt_stock_credit_id');
    }

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
