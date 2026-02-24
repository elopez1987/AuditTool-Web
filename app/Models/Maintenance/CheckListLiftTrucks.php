<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $serialnumber
 * @property string $supervisorname
 * @property string $horometro
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
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Maintenance\StorageLiftTruck $truck
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks forNumberTruck($numbertruck)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks forSerialNumber($serialnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks forWorkday($workday)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereHorometro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereSupervisorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListLiftTrucks whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CheckListLiftTrucks extends Model
{
    use LogsActivity;

    protected $table = 'checklistlifttrucks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'supervisorname',
        'horometro',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'createuser',
        'statusid',
    ];

    protected $hidden = [
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'MT-A',
        'schemaid' => 'STM',
    ];

    protected $casts = [
        //        'created_at' => 'datetime:d/m/Y H:s',
        //        'updated_At' => 'datetime:d/m/Y H:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return CheckListLiftTrucks::whereIn('checklistlifttrucks.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function truck()
    {
        return $this->belongsTo(StorageLiftTruck::class, 'serialnumber', 'serialnumber');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function scopeForSerialNumber($query, $serialnumber)
    {
        if (trim($serialnumber) != '') {
            $query->where('storagelifttrucks.serialnumber', '=', $serialnumber);
        }
    }

    public function scopeForNumberTruck($query, $numbertruck)
    {
        if (trim($numbertruck) != '') {
            $query->where('storagelifttrucks.numbertruck', '=', $numbertruck);
        }
    }

    public function scopeForWorkday($query, $workday)
    {
        if ($workday != '') {
            $query->whereRaw('CAST(checklistlifttrucks.created_at AS DATE) = ?', $workday);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (CheckListLiftTrucks $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
