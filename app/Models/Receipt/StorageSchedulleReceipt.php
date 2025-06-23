<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageSchedulleReceipt extends Model
{
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

    protected $casts = [
//        'workday' => 'date',
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
//                $model->userid = \Auth::id();
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

    public function secuence(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'secuence');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptid');
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
