<?php

namespace App\Models\Inventory\PartNumber;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryPartnumber extends Model
{
    use LogsActivity;

    protected $table = 'inventorypartnumbers';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'category01id',
        'category02id',
        'prtnum',
        'lngdsc',
        'abccod',
        'shelflife',
        'stkuom',
        'palnetwgt',
        'palgrswgt',
        'caslvl',
        'untcas',
        'untpal',
        'palwid',
        'pallen',
        'palhgt',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'barcode',
        'statusid',
        'codeean',
        'codedun',
        'configurationflag',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'partnumberid' => 'string',
        'configurationflag' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryPartnumber::whereIn('inventorypartnumbers.warehouseid', $warehouses)
            ->whereIn('inventorypartnumbers.centerid', $centers);
    }

    public static function partnumberActiveToSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryPartnumber::whereStatusid('E')
            ->whereIn('inventorypartnumbers.centerid', $centers)
            ->whereIn('inventorypartnumbers.warehouseid', $warehouses)
            ->pluck('prtnum', 'id')->toArray();
    }

    public function scopePartNumber($query, $prtnum)
    {
        if (trim($prtnum) !== '') {
            $query->where('inventorypartnumbers.prtnum', '=', $prtnum);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('inventorypartnumbers.barcode', '=', $barcode);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (empty($statusid)) {
            $query->where('inventorypartnumbers.statusid', 'E');
        } else {
            $query->where('inventorypartnumbers.statusid', $statusid);
        }
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function category01(): BelongsTo
    {
        return $this->belongsTo(InventoryPartNumberCategory01::class, 'category01id', 'id');
    }

    public function category02(): BelongsTo
    {
        return $this->belongsTo(InventoryPartNumberCategory02::class, 'category02id', 'id');
    }

    public function unitmeasure()
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'stkuom', 'unitmeasureid');
    }

    public static function searchPartNumber($request)
    {
        return DB::table('inventorypartnumbers')
            ->select('*')
            ->where('prtnum', '=', $request['partnumberid'])
            ->orWhere('barcode', '=', $request['partnumberid'])
            ->first();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (InventoryPartnumber $model) {
                $model->auxiliary05 = Auth::user()->username;
            });
        }
    }
}
