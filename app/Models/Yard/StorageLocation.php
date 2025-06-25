<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @method static whereStatusid(string $string)
 */
class StorageLocation extends Model
{

    use Notifiable;
    use SoftDeletes;

    protected $table = 'storagelocations';
    protected $dateFormat = 'Y-m-d H:i:s';

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'centerid',
        'warehouseid',
        'zoneid',
        'name',
        'locationid',
        'barcode',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageLocation $model) {
                $model->auxiliary05 = Auth::user()->username;
                $model->barcode = rand(100000, 999999);
            });
            self::updating(function (StorageLocation $model) {
                $model->auxiliary05 = Auth::user()->username;
            });
        }
    }


    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return StorageLocation::whereIn('storagelocations.centerid', $centers)
            ->whereIn('storagelocations.warehouseid', $warehouses);
    }


//    Relaciones
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id');
    }

//    Filtros Scopes
    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('storagelocations.name', 'like', "%$name%");
        }
    }

    public function scopeZone($query, $zoneid)
    {
        if ($zoneid != "") {
            $query->where('storagelocations.zoneid', $zoneid);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if ($statusid != "") {
            $query->where('storagelocations.statusid', $statusid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if ($warehouseid != "") {
            $query->where('storagelocations.warehouseid', $warehouseid);
        }
    }


//    Funciones auxiliares para otros modulos
    static function searchLocationForCode($barcode)
    {
        return StorageLocation::controlAccessUser()
            ->where('barcode', '=', $barcode)
            ->select(['id', 'name', 'locationid', 'barcode', 'zoneid', 'statusid'])
            ->first();
    }

    static function updateLocation($locationid, $statusid, $boxnumber)
    {
        StorageLocation::controlAccessUser()
            ->where('id', $locationid)
            ->update(['statusid' => $statusid, 'auxiliary01' => $boxnumber]);
    }

    public static function activeLocationForZone()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageLocation::whereStatusid('E')
            ->whereIn('storagelocations.centerid', $centers)
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->select(['name', 'id'])->get();
    }


}
