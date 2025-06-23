<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;

class StorageAssignLiftTrucks extends Model
{
    protected $table = 'storageassignlifttrucks';

    protected $fillable = [
        'centerid', 'createuser', 'typeid', 'statusid', 'username',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];


    protected $hidden = [
        'schemaid'
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


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageAssignLiftTrucks $model) {
                $model->createuser = Auth::user()->username;
            });
        }
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


}
