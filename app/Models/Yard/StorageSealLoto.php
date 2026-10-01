<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSealLoto extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSealLoto $model) {
                $model->createuser = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {

        $username = Auth::user()->username;

        return StorageSealLoto::query()
            ->whereIn(
                'storagesealloto.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            );
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $description)
    {
        if (! empty($description)) {
            $query->where('storagesealloto.description', 'like', "%{$description}%");
        }
    }
}
