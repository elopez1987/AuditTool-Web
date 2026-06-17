<?php

namespace App\Models\SafetyTours\Incidents;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageIncidenceSafety extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storage_incidence_safeties';

    protected $fillable = [
        'centerid',
        'stotage_category_safety_id',
        'storage_safety_category_detail_id',
        'name',
        'description',
        'created_by',
        'closed_by',
        'closed_at',
        'location',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
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

    public function stotagecategorysafety(): BelongsTo
    {
        return $this->belongsTo(StorageCategorySafety::class, 'storage_category_safety_id');
    }

    public function storagesafetycategorydetail(): BelongsTo
    {
        return $this->belongsTo(StorageSafetyCategoryDetail::class);
    }

    protected $attributes = [
        'statusid' => 'IRS-01',
        'schemaid' => 'IRS',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'closed_at' => 'timestamp',
        ];
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageCategorySafety::whereIn('storage_category_safeties.centerid', $centers);
    }
}
