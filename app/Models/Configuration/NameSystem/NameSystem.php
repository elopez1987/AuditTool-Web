<?php

namespace App\Models\Configuration\NameSystem;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static get()
 * @property int $id
 * @property string $name
 * @property string|null $version
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NameSystem whereVersion($value)
 * @mixin \Eloquent
 */
class NameSystem extends Model
{
    protected $table = 'systemname';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'name',
        'version',
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
}
