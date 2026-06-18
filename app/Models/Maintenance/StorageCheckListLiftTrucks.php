<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $name
 * @property string $schematypeid
 * @property string $typeid
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $auxiliary06
 * @property string|null $auxiliary07
 * @property string|null $auxiliary08
 * @property string|null $auxiliary09
 * @property string|null $auxiliary10
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemStatus|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks forName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereSchematypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListLiftTrucks withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageCheckListLiftTrucks extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'storagechecklistslifttrucks';

    protected $fillable = [
        'centerid',
        'name',
        'schematypeid',
        'typeid',
        'configurationflag',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
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

        return StorageCheckListLiftTrucks::whereIn('storagechecklistslifttrucks.centerid', $centers);
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

    public function scopeForName($q, $name)
    {
        if ($name !== '') {
            $q->where('storagechecklistslifttrucks.name', 'like', "%{$name}%");
        }
    }
}
