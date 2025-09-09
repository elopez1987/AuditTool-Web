<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptTruck extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;
    protected $table = 'receipttrucks';

    protected $casts = [
        'entrydate' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'carrierid',
        'operatorname',
        'phone',
        'drivernumber',
        'entrydate',
        'finishdate',
        'trucknumber',
        'econumber',
        'type',
        'createuser',
        'updateuser',
        'statusid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'brand',
        'typeoflicense',
        'validity',
        'drivernumber',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
        'statusid' => 'OU',
        'type' => 'Truck'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptTruck $model) {
                $model->createuser = Auth::user()->id;
                $model->entrydate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptTruck::whereIn('receipttrucks.warehouseid', $warehouses)
            ->whereIn('receipttrucks.centerid', $centers);
    }

//    Relaciones
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

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != "") {
            $query->where('receipttrucks.id', '=', $folionumber);
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) != "") {
            $query->where('receipttrucks.trucknumber', 'like', "%$trucknumber%");
        }
    }

    public function scopeTruck($query, $trucknumber)
    {
        if (trim($trucknumber) != '') {
            $query->where('receipttrucks.trucknumber', '=', $trucknumber);
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) != "") {
            $query->where('receipttrucks.econumber', 'like', "%$econumber%");
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != "") {
            $query->where('receipttrucks.barcode', 'like', "%$barcode%");
        }
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('receipttrucks.operatorname', 'like', "%$name%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != "") {
            $query->where('receipttrucks.carrierid', '=', $carrierid);
        }
    }
    public function scopeFilterForRangeDate($query, $startDate, $endDate)
    {
        if ($startDate != '' && $endDate != '') {
            $query->whereBetween('receipttrucks.entrydate', [$startDate, $endDate]);
        } else {
            $query->whereBetween('receipttrucks.entrydate', [Carbon::now()->subDays(90), Carbon::now()]);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != "") {
            $query->where('receipttrucks.warehouseid', '=', $warehouseid);
        }

    }

//    Filtros de busqueda
    static function searchTrucksForCarrier($carrierid)
    {
        return ReceiptTruck::controlAccessUser()->where('receipttrucks.carrierid', $carrierid)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->get();
    }

    static function searchTruckForTruckNumber($trucknumber)
    {
        return ReceiptTruck::controlAccessUser()
            ->where('receipttrucks.trucknumber', '=', $trucknumber)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->first();
    }

    static function searchTruckForBarcode($barcode)
    {
        return ReceiptTruck::controlAccessUser()->whereBarcode($barcode)
            ->whereNotIn('receipttrucks.statusid', ['TR'])->first();
    }


}
