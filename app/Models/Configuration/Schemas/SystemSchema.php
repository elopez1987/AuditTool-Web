<?php

namespace App\Models\Configuration\Schemas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static get()
 *
 * @property mixed schemaid
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
