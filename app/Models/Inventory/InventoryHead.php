<?php

namespace App\Models\Inventory;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Str;

class InventoryHead extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'inventory_heads';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'created_id',
        'startdate',
        'finishdate',
        'schemaid',
        'statusid',
        'closed_id',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function created(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    public function closed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'startdate' => 'datetime',
            'finishdate' => 'datetime',
        ];
    }

    protected $attributes = [
        'statusid' => 'INF-01',
        'schemaid' => 'INF',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->inventory_uuid = (string) Str::uuid();
        });
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryHead::whereIn('inventory_heads.centerid', $centers)
            ->whereIn('inventory_heads.warehouseid', $warehouses);
    }
}
