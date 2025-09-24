<?php

namespace App\Models\Yard\ValidateOperatorInRControl;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $centerid
 * @property int $orderid
 * @property string $operatorname
 * @property string $statusoperator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $userid
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereStatusoperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageValidateOperator whereUserid($value)
 * @mixin \Eloquent
 */
class StorageValidateOperator extends Model
{
    public $timestamps = false;
    protected $table = 'storagevalidateoperators';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'orderid', 'operatorname', 'statusoperator', 'created_at',
    ];

    protected $hidden = [
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageValidateOperator::whereIn('storagevalidateoperators.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageValidateOperator $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
