<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
        if (trim($serialnumber) !== '') {
            $query->where('storagelifttrucks.serialnumber', '=', $serialnumber);
        }
    }

    public function scopeForNumberTruck($query, $numbertruck)
    {
        if (trim($numbertruck) !== '') {
            $query->where('storagelifttrucks.numbertruck', '=', $numbertruck);
        }
    }

    public function scopeForWorkday($query, $workday)
    {
        if ($workday !== '') {
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
