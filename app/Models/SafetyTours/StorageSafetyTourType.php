<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSafetyTourType extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagesafetytourtypes';


    protected $fillable = [
        'centerid', 'name', 'typeuuid', 'userid', 'statusid', 'value01', 'value02', 'value03'
    ];

    protected $attributes = [
        'statusid' => 'TYP-01',
        'schemaid' => 'TYP',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageSafetyTourType $model) {
                $model->userid = Auth::id();
                $model->typeuuid = (string)Str::uuid();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTourType::whereIn('storagesafetytourtypes.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            return $query->where('name', 'LIKE', "%$name%");
        }
    }

}
