<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageCategorySafety extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storage_category_safeties';

    protected $fillable = [
        'centerid',
        'name',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'created_by',
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

    public function category_details(): StorageCategorySafety|HasMany
    {
        return $this->hasMany(StorageSafetyCategoryDetail::class, 'storage_category_safety_id', 'id');
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

        return StorageCategorySafety::whereIn('storage_category_safeties.centerid', $centers);
    }
}
