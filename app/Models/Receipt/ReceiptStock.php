<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
        'stockcategoryid',
        'expirecode',
        'expiredate',
        'productiondate',
        'quantity',
        'rotary',
        'definitive',
    ];

    //    Relaciones entre modelos
    public function partnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }
}
