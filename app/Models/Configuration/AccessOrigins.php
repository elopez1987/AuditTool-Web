<?php

namespace App\Models\Configuration;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Yard\StorageUnitType;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessOrigins extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'securityaccessorigins';

    protected $fillable = [
        'centerid',
        'storageunittype_id',
        'username',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function storageunittype(): BelongsTo
    {
        return $this->belongsTo(StorageUnitType::class, 'storageunittype_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
