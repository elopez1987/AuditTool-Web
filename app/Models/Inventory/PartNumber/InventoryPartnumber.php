<?php

namespace App\Models\Inventory\PartNumber;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
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
        'shelflife',
        'stkuom',
        'untcas',
        'untpal',
        'barcode',
        'statusid',
        'codeean',
        'codedun',
        'configurationflag',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
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

    public static function controlAccessUser(): Builder
    {
        $username = Auth::user()->username;

        return InventoryPartnumber::query()
            ->whereIn(
                'inventorypartnumbers.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            );
    }

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (InventoryPartnumber $model) {});
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

    //	Filtros

    public function scopePartNumber($query, $prtnum)
    {
        if (trim($prtnum) != '') {
            $query->where('inventorypartnumbers.prtnum', '=', $prtnum);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != '') {
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
}
