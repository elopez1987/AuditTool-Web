<?php

namespace App\Models\Configuration\TablesDataBase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $tableid
 * @property string $description
 * @property string $notes
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable forName($tableid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereTableid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemTable whereValue03($value)
 *
 * @mixin \Eloquent
 */
class SystemTable extends Model
{
    public $incrementing = false;

    protected $table = 'systemtables';

    protected $keyType = 'string';

    protected $primaryKey = 'tableid';

    protected $fillable = [
        'tableid',
        'description',
        'notes',
    ];

    protected $attributes = [
        'description' => '0',
        'notes' => '0',
    ];

    public function scopeForName($query, $tableid)
    {
        if (trim($tableid) !== '') {
            $query->where('systemtables.tableid', 'like', "%{$tableid}%");
        }
    }
}
