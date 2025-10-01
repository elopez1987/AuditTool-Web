<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $username
 * @property string $schematypeid
 * @property string $typeid
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $createuser
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User|null $assigntruck
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemStatus|null $type
 * @property-read User|null $usertruck
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereSchematypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAssignLiftTrucks whereUsername($value)
 * @mixin \Eloquent
 */
class StorageAssignLiftTrucks extends Model
{
    use LogsActivity;

    protected $table = 'storageassignlifttrucks';

    protected $fillable = [
        'centerid', 'createuser', 'typeid', 'statusid', 'username',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $hidden = [
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
        'schematypeid' => 'TPM',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_At' => 'datetime:d/m/Y H:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageAssignLiftTrucks::whereIn('storageassignlifttrucks.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function type()
    {
        return $this->belongsTo(SystemStatus::class, 'typeid', 'statusid');
    }

    public function usertruck()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function assigntruck()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageAssignLiftTrucks $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
