<?php

namespace App\Models\Configuration\Schemas;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $schemaid)
 * @method static create(array $array)
 * @method static whereSchemaid(string $string)
 *
 * @property string|null $centerid
 * @property string|null $warehouseid
 * @property string $schemaid
 * @property string $statusid
 * @property string $name
 * @property string|null $comment
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemStatus whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class SystemStatus extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected $table = 'systemstatus';

    protected $primaryKey = 'statusid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'schemaid',
        'statusid',
        'name',
        'comment',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function SchemaSTS()
    {
        return SystemStatus::whereSchemaid('STS')
            ->whereNotIn('statusid', ['B'])->pluck('name', 'statusid')->toArray();
    }

    public static function SchemaRUS()
    {
        return SystemStatus::whereSchemaid('RUS')->pluck('name', 'statusid')->toArray();
    }

    public static function SchemaSRE()
    {
        return SystemStatus::whereSchemaid('SRE')->pluck('name', 'statusid')->toArray();
    }

    public static function SchemaSCHIndex()
    {
        return SystemStatus::whereSchemaid('KLE')
            ->whereAuxiliary01('F')
            ->pluck('systemstatus.name', 'statusid')->toArray();
    }

    public static function SchemaEIJ()
    {
        return SystemStatus::where('schemaid', 'EIJ')
            ->whereIn('statusid', ['AB', 'CD'])
            ->pluck('systemstatus.name', 'statusid')->toArray();
    }

    public static function SchemaEIJDetail()
    {
        return SystemStatus::where('schemaid', 'EIJ')
            ->whereIn('systemstatus.statusid', ['P', 'N'])
            ->pluck('name', 'statusid')->toArray();
    }
}
