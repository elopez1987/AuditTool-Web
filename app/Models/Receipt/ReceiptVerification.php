<?php

declare(strict_types=1);

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $quantity
 */
class ReceiptVerification extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receipt_verifications';

    protected $hidden = ['request_fingerprint', 'balance_snapshot'];

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
        'receiptstock_id',
        'receipt_stock_credit_id',
        'client_operation_id',
        'request_fingerprint',
        'balance_snapshot',
        'catetory_incidence_id',
        'evidence_path',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(StorageCategoryIncidence::class, 'catetory_incidence_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'balance_snapshot' => 'array',
            'productiondate' => 'date',
            'expiredate' => 'date',
        ];
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(ReceiptStock::class, 'receiptstock_id');
    }

    public function stockcredit(): BelongsTo
    {
        return $this->belongsTo(ReceiptStockCredit::class, 'receipt_stock_credit_id');
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
