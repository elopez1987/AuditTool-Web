<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageTags extends Model
{
    use softDeletes;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagetags';

    protected $fillable = [
        'centerid',
        'tagid',
        'taguuid',
        'name',
        'latitude',
        'longitude',
        'createuser',
        'value01',
        'value02',
        'value03',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'TAG-01',
        'schemaid' => 'TAG',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageTags $model) {
                $model->createuser = Auth::id();
                $model->taguuid = (string)Str::uuid();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageTags::whereIn('storagetags.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            return $query->where('tagid', 'LIKE', "%$name%");
        }
    }

}
