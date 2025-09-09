<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Receipt\StorageReceiptPeriods\StorageReceiptPeriod;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSchedulleReceipt extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storageschedullereceipt';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'periodid',
        'workday',
        'turnid',
        'secuence',
        'receiptid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'schemaid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'DPR-01',
        'schemaid' => 'DPR',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageSchedulleReceipt $model) {
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSchedulleReceipt::whereIn('storageschedullereceipt.warehouseid', $warehouses)
            ->whereIn('storageschedullereceipt.centerid', $centers);
    }


    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function turn(): BelongsTo
    {
        return $this->belongsTo(StorageTurn::class, 'turnid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function statussecuence(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'secuence', 'statusid');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptid', 'id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(StorageReceiptPeriod::class, 'periodid');

    }


//    Filtros
    public function scopeWorkday($query, $workday)
    {
        if (trim($workday) != "") {
            return $query->where('storageschedullereceipt.workday', '=', $workday);
        }
    }

    public function scopeShipment($query, $shipment)
    {
        if (trim($shipment) != "") {
            $query->where('storageschedullereceipt.shipmentid', '=', $shipment);
        }
    }


}
