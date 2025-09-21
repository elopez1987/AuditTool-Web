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
