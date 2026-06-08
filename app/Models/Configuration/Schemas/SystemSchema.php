<?php

namespace App\Models\Configuration\Schemas;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @method static get()
 *
 * @property mixed schemaid
 * @property string $schemaid
 * @property string $name
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, SystemStatus> $status
 * @property-read int|null $status_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema schema($schemaid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSchema whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SystemSchema extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected $table = 'systemschemas';

    protected $primaryKey = 'schemaid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'schemaid',
        'name',
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

    public function status(): HasMany
    {
        return $this->hasMany(SystemStatus::class, 'schemaid', 'schemaid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('systemschemas.name', 'like', "%{$name}%");
        }
    }

    public function scopeSchema($query, $schemaid)
    {
        if (trim($schemaid) !== '') {
            $query->where('systemschemas.schemaid', 'like', "%{$schemaid}%");
        }
    }
}
