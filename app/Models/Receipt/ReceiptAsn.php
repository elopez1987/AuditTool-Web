<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageReason;
use App\Observers\Receipt\ReceiptAsnObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy([ReceiptAsnObserver::class])]
class ReceiptAsn extends Model
{
    use LogsActivity;

    protected $table = 'receiptasn';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid', 'warehouseid', 'providerid', 'shipmentid', 'billoflading',
        'addasndate', 'cancellationdate', 'goodissuedate', 'created_by', 'closed_by',
        'razonstatusid', 'folionumber', 'stockcategoryid', 'statusid', 'workday',
    ];

    protected $attributes = [
        'schemaid' => 'SRE',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser(): Builder
    {
        $username = Auth::user()->username;

        return ReceiptAsn::query()
            ->whereIn(
                'receiptasn.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            )
            ->whereIn(
                'receiptasn.warehouseid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('warehouseid'),
            );
    }

    //    ------------------------------ Relaciones ------------------------------------------
    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid');
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function inventorystock(): BelongsTo
    {
        return $this->belongsTo(InventoryStockCategory::class, 'stockcategoryid', 'id');
    }

    public function timereceipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsnTime::class, 'id', 'receiptid');
    }

    //  -----------------------------------------  Funciones auxiliares -------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptAsn $model) {
                $model->created_by = Auth::id();
            });
        }
    }
}
