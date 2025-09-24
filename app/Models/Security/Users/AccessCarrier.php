<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property int $carrierid
 * @property string $username
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccessCarrier whereUsername($value)
 * @mixin \Eloquent
 */
class AccessCarrier extends Model
{
    public $timestamps = false;
    protected $table = 'securityaccesscarriers';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'username', 'carrierid',
    ];
}
