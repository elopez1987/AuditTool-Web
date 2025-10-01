<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static whereStatusid(string $string)
 * @property int $id
 * @property string $name
 * @property string $razonstatusid
 * @property string $type
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereRazonstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorageReason extends Model
{
    protected $table = 'storagereasons';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'razonstatusid',
        'type',
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

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storagereasons.name', 'like', "%{$name}%");
        }
    }
}
