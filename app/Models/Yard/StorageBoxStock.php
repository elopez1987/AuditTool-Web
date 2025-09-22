<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageBoxStock extends Model
{
    protected $table = 'vw_storageboxstock';

    protected $casts = [
        'entrydate' => 'datetime:d/m/Y H:i',
        'deliverdate' => 'datetime:d/m/Y H:i',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return StorageBoxStock::whereIn('vw_storageboxstock.centerid', $centers)
            ->whereIn('vw_storageboxstock.warehouseid', $warehouses);
    }
    public function getOperatorNameAttribute($value)
    {
        return ucwords($value);
    }
    public function center()
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

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('vw_storageboxstock.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('vw_storageboxstock.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('vw_storageboxstock.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) !== '') {
            $query->where('vw_storageboxstock.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeReason($query, $razonstatusid)
    {
        if (trim($razonstatusid) !== '') {
            $query->where('receiptunits.razonstatusid', $razonstatusid);
        }
    }
}
