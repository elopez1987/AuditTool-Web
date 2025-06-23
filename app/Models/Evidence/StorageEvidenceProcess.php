<?php

namespace App\Models\Evidence;

use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageReason;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageEvidenceProcess extends Model
{
    protected $table = 'storageevidenceoperations';
    protected $primaryKey = 'recordid';
    protected $keyType = 'integer';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'turnid',
        'workday',
        'appointment',
        'customerid',
        'econumber',
        'carrierid',
        'boxnumber',
        'providerid',
        'locationid',
        'razonstatusid',
        'createuser',
        'stockcategoryid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'schemaid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageEvidenceProcess $model) {
                $model->createuser = Auth::user()->username;
                $model->appointment = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageEvidenceProcess::whereIn('storageevidenceoperations.warehouseid', $warehouses)
            ->whereIn('storageevidenceoperations.centerid', $centers);
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function origen()
    {
        return $this->belongsTo(InventoryStockCategory::class, 'stockcategoryid', 'id');
    }

    public function razon()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }


    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) != "") {
            $query->where('storageevidenceoperations.statusid', '=', $statusid);
        } else {
            $query->where('storageevidenceoperations.statusid', '=', 'E');
        }
    }

    public function scopeForWorkDay($query, $workday)
    {
        if (trim($workday) != '') {
            $query->where('storageevidenceoperations.workday', $workday);
        }
    }


    public function scopeForRangeDate($query, $from_date, $to_date)
    {
        if (trim($from_date) != '' && trim($to_date) != '') {
            $query->whereBetween('storageevidenceoperations.workday', [$from_date, $to_date]);
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storageevidenceoperations.shipmentid', $shipmentid);
        }
    }

    public function scopeForCustomer($query, $customerid)
    {
        if (trim($customerid) != '') {
            $query->where('storageevidenceoperations.customerid', $customerid);
        }
    }

    public function scopeForTurn($query, $turnid)
    {
        if (trim($turnid) != '') {
            $query->where('storageevidenceoperations.turnid', $turnid);
        }
    }

    public function scopeForCategory($query, $categoryid)
    {
        if (trim($categoryid) != '') {
            $query->where('storageevidenceoperations.stockcategoryid', $categoryid);
        }
    }

}
