<?php

namespace App\Models\Inventory\InventoryFiscal;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    public function inventorystocksap(): HasMany
    {
        return $this->hasMany(InventoryStockSap::class, 'inventory_head_id', 'id');
    }

    public function inventorystockwms(): HasMany
    {
        return $this->hasMany(ImportInventoryWms::class, 'inventory_head_id', 'id');
    }

    public function inventorysupervisors(): HasMany
    {
        return $this->hasMany(InventorySupervisor::class, 'inventory_head_id', 'id');
    }

    public function inventoryaccounts(): HasMany
    {
        return $this->hasMany(InventoryAccountant::class, 'inventory_head_id', 'id');
    }

    public function inventorylocationevidences(): HasMany
    {
        return $this->hasMany(InventoryLocationEvidences::class, 'inventory_head_id', 'id');
    }

    public function inventoryassigncountercards(): HasMany
    {
        return $this->hasMany(InventoryAssignCounterCard::class, 'inventory_head_id', 'id');
    }

    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_id', 'id')
            ->withDefault(['name' => '']);
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
            $model->created_id = auth()->id();
        });
        self::deleting(function (InventoryHead $model) {
            $model->inventorystocksap()->delete();
            $model->inventorystockwms()->delete();
            $model->inventorysupervisors()->delete();
            $model->inventoryaccounts()->delete();
            $model->inventorylocationevidences()->delete();
            $model->inventoryassigncountercards()->delete();
        });
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryHead::whereIn('inventory_heads.centerid', $centers)
            ->whereIn('inventory_heads.warehouseid', $warehouses);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['name'] ?? null, function ($q, $name) {
                $q->where('name', 'LIKE', "%{$name}%");
            });
        //            ->when($filters['warehouse_id'] ?? null, function ($q, $warehouseId) {
        //                $q->where('warehouse_id', $warehouseId);
        //            })
        //            ->when($filters['date_from'] ?? null, function ($q, $dateFrom) {
        //                $q->whereDate('created_at', '>=', $dateFrom);
        //            })
        //            ->when($filters['date_to'] ?? null, function ($q, $dateTo) {
        //                $q->whereDate('created_at', '<=', $dateTo);
        //            })
        //            ->when($filters['search'] ?? null, function ($q, $search) {
        //                $q->where(function ($q) use ($search) {
        //                    $q->where('folio', 'LIKE', "%{$search}%")
        //                        ->orWhere('reference', 'LIKE', "%{$search}%");
        //                });
        //            });
    }
}
