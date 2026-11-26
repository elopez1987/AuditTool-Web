<?php

declare(strict_types=1);

namespace App\Models\Receipt;

use App\Casts\TrimmedDecimal;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $receiptid
 * @property int $partnumberid
 */
class ReceiptStock extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receiptstock';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'receiptid',
        'partnumberid',
        'quantity',
        'rotary',
        'definitive',
        'invoice',
        'selled',
        'creditnote',
        'gifts',
        'loaded',
        'unitmeasureid',
        'productiondate',
        'expiredate',
        'expirecode',
    ];

    protected function casts(): array   // Laravel 11+
    {
        return [
            'quantity' => TrimmedDecimal::class,
            'rotary' => TrimmedDecimal::class,
            'definitive' => TrimmedDecimal::class,
            'invoice' => TrimmedDecimal::class,
            'selled' => TrimmedDecimal::class,
            'creditnote' => TrimmedDecimal::class,
            'gifts' => TrimmedDecimal::class,
            'loaded' => TrimmedDecimal::class,
        ];
    }

    //    Relaciones entre modelos
    public function partnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function unitmeasure(): BelongsTo
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'unitmeasureid');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(ReceiptVerification::class, 'receiptstock_id');
    }
}
