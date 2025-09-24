<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $message
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiLog whereMessage($value)
 * @mixin \Eloquent
 */
class EdiLog extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $table = 'logimports';
    protected $keyType = 'integer';

    protected $fillable = [
        'message',
        'created_at',
    ];
}
