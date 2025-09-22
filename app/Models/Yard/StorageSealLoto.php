<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSealLoto extends Model
{
    use LogsActivity;

    public $timestamps = true;
    protected $table = 'storagesealloto';

    protected $fillable = [
        'centerid', 'description', 'boxnumber', 'createuser',
        'value01', 'value02', 'value03', 'value04', 'value05', 'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSealLoto::whereIn('storagesealloto.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $description)
    {
        if (! empty($description)) {
            $query->where('storagesealloto.description', 'like', "%{$description}%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSealLoto $model) {
                $model->createuser = \Auth::id();
            });
        }
    }
}
