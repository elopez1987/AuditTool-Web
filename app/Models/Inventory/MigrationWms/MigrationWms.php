<?php

namespace App\Models\Inventory\MigrationWms;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MigrationWms extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'migration_wms';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'locationd',
        'lpnizel',
        'lpnsap',
        'create_by',
        'value01',
        'value02',
        'value03',
        'photo',
    ];

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function createduser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }
}
