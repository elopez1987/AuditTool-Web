<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageCheckListLiftTrucks extends Model
{
    protected $primaryKey = "id";
    protected $table = 'storagechecklistslifttrucks';

    use SoftDeletes;

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
        'schematypeid' => 'TPM'
    ];


    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_At' => 'datetime:d/m/Y H:s',
    ];


//    protected static function boot()
//    {
//        parent::boot();
//        if (!app()->runningInConsole()) {
//            self::creating(function (StorageCheckListLiftTrucks $model) {
//                $model->createuser = Auth::user()->username;
//            });
//        }
//    }

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
        if ($name != '') {
            $q->where('storagechecklistslifttrucks.name', 'like', "%$name%");
        }
    }


}
