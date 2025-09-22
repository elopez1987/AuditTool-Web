<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSafetyTours extends Model
{
    use LogsActivity;

    protected $table = 'storagesafetytours';
    protected $fillable = [
        'centerid',
        'touruuid',
        'name',
        'datetours',
        'sequence',
        'userid',
        'value01',
        'value02',
        'value03',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'RSE-01',
        'schemaid' => 'RSE',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTours::whereIn('storagesafetytours.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

//    Filtros de busqueda
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            return $query->where('touruuid', 'LIKE', "%{$name}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSafetyTours $model) {
                $model->userid = Auth::id();
                $model->touruuid = (string) Str::uuid();
            });
        }
    }
}
