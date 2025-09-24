<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folioid
 * @property string $name
 * @property string $team
 * @property int $turnid
 * @property string $workday
 * @property int $userid
 * @property int|null $closeuserid
 * @property string|null $namesupervisor
 * @property string|null $keysupervisor
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $schemakeyid
 * @property string|null $statuskeyid
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read SystemStatus|null $statuskey
 * @property-read StorageTurn $turn
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereCloseuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereKeysupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereNamesupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereSchemakeyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereStatuskeyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentFolios whereWorkday($value)
 * @mixin \Eloquent
 */
class StorageShipmentFolios extends Model
{
    protected $table = 'storageshipmentfolios';

    protected $fillable = [
        'centerid', 'warehouseid', 'name', 'turnid', 'team', 'workday',
        'folioid', 'statusid', 'created_at',
        'updated_at', 'closeuserid', 'namesupervisor', 'keysupervisor', 'schemakeyid', 'statuskeyid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
        'statuskeyid' => 'FIR-01',
        'schemakeyid' => 'FIR',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageShipmentFolios::whereIn('storageshipmentfolios.warehouseid', $warehouses)
            ->whereIn('storageshipmentfolios.centerid', $centers);
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function statuskey()
    {
        return $this->belongsTo(SystemStatus::class, 'statuskeyid', 'statusid');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function ($model) {
                $model->userid = Auth::id();
                $model->name = Auth::user()->name;
                $model->workday = Carbon::now()->format('Y-m-d');
            });
        }
    }
}
