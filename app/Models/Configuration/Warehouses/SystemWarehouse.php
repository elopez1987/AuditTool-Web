<?php

namespace App\Models\Configuration\Warehouses;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method whereStatusid(string $string)
 * @method static select(string $string, string $string1, string $string2)
 * @method static whereIn(string $string, $warehouses)
 * @property string $centerid
 * @property string $warehouseid
 * @property string $name
 * @property string|null $center
 * @property string|null $warehouse
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $auxiliary06
 * @property string|null $auxiliary07
 * @property string|null $auxiliary08
 * @property string|null $auxiliary09
 * @property string|null $auxiliary10
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SystemCenterDistribution $centerdistribution
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse forName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemWarehouse whereWarehouseid($value)
 * @mixin \Eloquent
 */
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
        'configurationflag' => 'array',
        'center' => 'string',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return SystemWarehouse::whereIn('systemwarehouses.warehouseid', $warehouses)
            ->where('systemwarehouses.statusid', 'E');
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

//    Scopes
    public function scopeForName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('systemwarehouses.name', 'like', "%{$name}%");
        }
    }
}
