<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class CheckListTruckErrorLog extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;
    protected $table = 'checklistlifttruckerrorlog';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'scanuser',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (CheckListTruckErrorLog $model) {
                $model->scanuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return CheckListTruckErrorLog::whereIn('checklistlifttruckerrorlog.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }


}
