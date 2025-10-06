<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $truckcategoryid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentTruckCategory whereTruckcategoryid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentTruckCategory extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    public $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    protected $table = 'shipmenttruckcategories';

    protected $primaryKey = 'truckcategoryid';

    protected $keyType = 'string';

    protected $fillable = [
        'truckcategoryid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('statusid', '=', $statusid);
        }
    }
}
