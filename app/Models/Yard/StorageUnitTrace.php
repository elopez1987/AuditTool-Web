<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageUnitTrace extends Model
{
    protected $table = 'storageunittrace';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'receiptunitid',
        'barcode',
        'shipmentid',
        'carrierid',
        'truckid',
        'containerseal',
        'operatorname',
        'trucknumber',
        'boxnumber',
        'locationid',
        'zoneid',
        'phone',
        'razonstatusid',
        'unitstatusid',
        'drivernumber',
        'createuser',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function unit()
    {
        return $this->belongsTo(ReceiptUnit::class, 'receiptunitid', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')->withTrashed();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id')->withTrashed();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id')->withTrashed();
    }

    public function truck()
    {
        return $this->belongsTo(ShipmentTruck::class, 'truckid')->withTrashed();
    }

    public function unitstatus()
    {
        return $this->belongsTo(StorageUnitStatus::class, 'unitstatusid')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser')->withTrashed();
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storageunittrace.name', 'like', "%{$name}%");
        }
    }

    public function scopeBoxNumber($query, $box)
    {
        if (trim($box) !== '') {
            $query->where('storageunittrace.boxnumber', '=', $box);
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('storageunittrace.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if ($carrierid !== '') {
            $query->where('storageunittrace.carrierid', $carrierid);
        }
    }

    public function scopeDate($query, $datein, $dateout)
    {
        if ($datein !== '' && $dateout !== '') {
            $query->whereBetween('storageunittrace.created_at', [$datein, $dateout]);
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageUnitTrace::whereIn('storageunittrace.warehouseid', $warehouses)
            ->whereIn('storageunittrace.centerid', $centers);
    }

    public static function createTraceUnit($receiptunit, $reason)
    {
        StorageUnitTrace::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'receiptunitid' => $receiptunit->id,
            'barcode' => $receiptunit->barcode,
            'shipmentid' => $receiptunit->shipmentid,
            'carrierid' => $receiptunit->carrierid,
            'truckid' => $receiptunit->truckid,
            'containerseal' => $receiptunit->containerseal,
            'boxnumber' => $receiptunit->boxnumber,
            'locationid' => $receiptunit->locationid,
            'zoneid' => $receiptunit->zoneid,
            'razonstatusid' => $reason,
            'unitstatusid' => $receiptunit->unitstatusid,
            'createuser' => Auth::user()->id,
            'statusid' => $receiptunit->statusid,

            'trucknumber' => $receiptunit->tracto ? $receiptunit->tracto->trucknumber : '',
            'phone' => $receiptunit->tracto ? $receiptunit->tracto->phone : '',
            'operatorname' => $receiptunit->tracto ? $receiptunit->tracto->operatorname : '',
            'drivernumber' => $receiptunit->tracto ? $receiptunit->tracto->drivernumber : '',
        ]);
    }
}
