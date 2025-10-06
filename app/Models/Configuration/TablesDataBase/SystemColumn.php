<?php

namespace App\Models\Configuration\TablesDataBase;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $tableid
 * @property string $columnid
 * @property string|null $shortdesc
 * @property string|null $description
 * @property int $id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn whereColumnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn whereShortdesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemColumn whereTableid($value)
 *
 * @mixin \Eloquent
 */
class SystemColumn extends Model
{
    public $timestamps = false;

    protected $table = 'systemcolumns';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tableid',
        'description',
        'shortdesc',
        'columnid',
    ];
}
