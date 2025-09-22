<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageLiftTruck extends Model
{
    use SoftDeletes;

    protected $table = 'storagelifttrucks';
    protected $primaryKey = 'serialnumber';
    protected $keyType = 'string';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'numbertruck',
        'typeid',
        'brandid',
        'modelid',
        'axuiliary01',
        'axuiliary02',
        'axuiliary03',
        'axuiliary04',
        'axuiliary05',
        'statusid',
        'deleted_at',

    ];

    protected $hidden = [
//        'created_at',
        'updated_at',
        'deleted_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'MT-A',
        'schemaid' => 'STM',

        'schematypeid' => 'TPM',
        'schemabrandid' => 'MCM',
        'schemamodelid' => 'MDM',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_at' => 'datetime:d/m/Y H:s',
        'deleted_at' => 'datetime:d/m/Y H:s',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageLiftTruck::whereIn('storagelifttrucks.centerid', $centers);
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

    public function model()
    {
        return $this->belongsTo(SystemStatus::class, 'modelid', 'statusid');
    }

    public function brand()
    {
        return $this->belongsTo(SystemStatus::class, 'brandid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function scopeSerialNumber($q, $serialnumber)
    {
        if ($serialnumber !== '') {
            $q->where('serialnumber', $serialnumber);
        }
    }

    public function scopeNumberTruck($q, $numbertruck)
    {
        if ($numbertruck !== '') {
            $q->where('numbertruck', $numbertruck);
        }
    }

    public function scopeStatus($q, $statusid)
    {
        if ($statusid !== '') {
            $q->where('storagelifttrucks.statusid', $statusid);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageLiftTruck $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
