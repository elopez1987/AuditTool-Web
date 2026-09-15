<?php

namespace App\Models\Configuration\Warehouses;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Repositories\SystemWarehouseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemWarehouse extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected $table = 'systemwarehouses';

    protected $primaryKey = 'warehouseid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'center',
        'warehouseid',
        'warehouse',
        'name',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
        'statusid',
        'configurationflag',
        'notificationsflags',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    protected $casts = [
        'configurationflag' => 'array',
        'notificationsflags' => 'array',
        'center' => 'string',
    ];

    /**
     * Kept as a chainable query scope for backward compatibility with existing
     * callers. The actual data access (and its caching, for load speed) lives
     * in SystemWarehouseRepository::getAccessibleWarehouseIds(); this method
     * only resolves that repository and applies the Eloquent filter.
     */
    public static function controlAccessUser()
    {
        $warehouses = app(SystemWarehouseRepositoryInterface::class)->getAccessibleWarehouseIds();

        return static::whereIn('systemwarehouses.warehouseid', $warehouses);

    }

    //    Relaciones
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function centerdistribution(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }
}
