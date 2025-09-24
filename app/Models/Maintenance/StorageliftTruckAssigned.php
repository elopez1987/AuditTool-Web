<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $serialnumber
 * @property string $username
 * @property \Illuminate\Support\Carbon $startdate
 * @property \Illuminate\Support\Carbon|null $finishdate
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageliftTruckAssigned whereUsername($value)
 * @mixin \Eloquent
 */
class StorageliftTruckAssigned extends Model
{
    protected $table = 'storagelifttruckassigned';
    protected $primaryKey = 'serialnumber';
    protected $keyType = 'string';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'username',
        'startdate',
        'finishdate',
        'statusid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

//    MT-C = Asignado

    protected $attributes = [
        'statusid' => 'MT-C',
        'schemaid' => 'STM',
    ];

    protected $casts = [
        'startdate' => 'datetime:d/m/Y H:s',
        'finishdate' => 'datetime:d/m/Y H:s',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageliftTruckAssigned::whereIn('storagelifttruckassigned.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageliftTruckAssigned $model) {
                $model->startdate = Carbon::now();
            });
        }
    }
}
