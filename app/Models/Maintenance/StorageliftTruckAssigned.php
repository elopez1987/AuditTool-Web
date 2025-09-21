<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
