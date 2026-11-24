<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptVerification extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receipt_verifications';

    protected $fillable = [
        'receiptasn_id',
        'inventorypartnumber_id',
        'quantity',
        'type_id',
        'inventoryunitsmeasure_id',
        'productiondate',
        'expiredate',
        'expirecode',
        'created_by',
    ];

    public function receiptasn(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptasn_id', 'id');
    }

    public function inventorypartnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'inventorypartnumber_id', 'id');
    }

    public function inventoryunitsmeasure(): BelongsTo
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'inventoryunitsmeasure_id', 'id');
    }

    public function createdby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    protected function casts(): array
    {
        return [
            'productiondate' => 'date',
            'expiredate' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptVerification $model): void {
                $model->created_by = Auth::id();
            });
        }
    }
}
