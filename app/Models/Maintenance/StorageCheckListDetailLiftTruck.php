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
 * @property int $checklistid
 * @property string $description
 * @property string $typeid
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageCheckListLiftTrucks $checklist
 * @property-read SystemStatus $status
 * @property-read SystemStatus|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetailLiftTruck withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageCheckListDetailLiftTruck extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'storagechecklistdetaillifttrucks';

    protected $fillable = [
        'centerid', 'description', 'checklistid', 'typeid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
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

        return StorageCheckListDetailLiftTruck::whereIn('storagechecklistdetaillifttrucks.centerid', $centers);
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

    public function checklist()
    {
        return $this->belongsTo(StorageCheckListLiftTrucks::class, 'checklistid', 'id');
    }
}
