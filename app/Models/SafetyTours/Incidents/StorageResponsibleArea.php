<?php

namespace App\Models\SafetyTours\Incidents;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageResponsibleArea extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storage_safety_responsible_areas';

    protected $fillable = [
        'centerid',
        'name',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'configurationflag',
        'schemaid',
        'statusid',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function create_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'userid');
    }

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageResponsibleArea::whereIn('storage_safety_responsible_areas.centerid', $centers);
    }
}
