<?php

namespace App\Models\Yard\StorageZone;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @method static whereStatusid(string $string)
 */
class StorageZone extends Model
{
    protected $table = 'storagezones';
    protected $dateFormat = 'Y-m-d H:i:s';
    use LogsActivity;
    use SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    protected $fillable = [
        'centerid',
        'warehouseid',
        'zoneid',
        'name',
        'capacity',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];
    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];
    protected $casts = [
        'configurationflag' => 'array',
    ];
    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return StorageZone::whereIn('storagezones.centerid', $centers)
            ->whereIn('storagezones.warehouseid', $warehouses);
    }

//    --Relaciones entre modelos
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

//    --Filtros para modelos
    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('storagezones.name', 'like', "%$name%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != "") {
            $query->where('storagezones.warehouseid', '=', $warehouseid);
        }
    }

}
