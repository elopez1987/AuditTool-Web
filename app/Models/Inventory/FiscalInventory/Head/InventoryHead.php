<?php

namespace App\Models\Inventory\FiscalInventory\Head;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryHead extends Model
{
    use LogsActivity;

    protected $table = 'inventoryhead';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        "centerid",
        "warehouseid",
        "schematypeid",
        "statustypeid",
        "description",
        "createdate",
        "createuser",
        "startdate",
        "finishdate",
        "canceldate",
        "canceluser",
        "auxiliary01",
        "auxiliary02",
        "auxiliary03",
        "auxiliary04",
        "auxiliary05",
        "statusid",
        'inventorytype'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'schematypeid' => 'INC',
        'schemaid' => 'IBX',
        'statusid' => 'PN'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryHead::whereIn('inventoryhead.centerid', $centers)
            ->whereIn('inventoryhead.warehouseid', $warehouses);
    }


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (InventoryHead $model) {
                $model->createuser = Auth::user()->username;
                $model->createdate = Carbon::now();
            });
        }
    }


    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function type()
    {
        return $this->belongsTo(SystemStatus::class, 'statustypeid', 'statusid')
            ->where('schemaid', '=', 'INC');
    }


    public function scopeForStatus($query, $status)
    {
        if (!empty($status)) {
            $query->where('inventoryhead.statusid', $status);
        } else {
            $query->whereIn('inventoryhead.statusid', ['AT', 'PN']);
        }
    }


}
