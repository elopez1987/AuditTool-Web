<?php

namespace App\Models\Configuration\Parameters;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $parameterid
 * @property string $name
 * @property string|null $configurationflag
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, SystemDetailParameter> $detailparameter
 * @property-read int|null $detailparameter_count
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter forName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter forParameter($parameterid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereParameterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemParameter withoutTrashed()
 *
 * @mixin \Eloquent
 */
class SystemParameter extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    public $keyType = 'string';

    protected $table = 'systemparameters';

    protected $primaryKey = 'parameterid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'parameterid',
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
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function detailparameter()
    {
        return $this->hasMany(SystemDetailParameter::class, 'parameterid', 'parameterid');
    }

    public function scopeForName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('systemparameters.name', 'like', "%{$name}%");
        }
    }

    public function scopeForParameter($query, $parameterid)
    {
        if (trim($parameterid) !== '') {
            $query->where('systemparameters.parameterid', 'like', "%{$parameterid}%");
        }
    }
}
