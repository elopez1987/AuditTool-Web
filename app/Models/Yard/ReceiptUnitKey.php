<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folionumber
 * @property string|null $containerseal
 * @property string|null $nameoperator01
 * @property string|null $keyoperator01
 * @property string|null $nameoperator02
 * @property string|null $keyoperator02
 * @property string|null $nameoperator03
 * @property string|null $keyoperator03
 * @property string|null $nameguard01
 * @property string|null $keyguard01
 * @property string|null $nameguard02
 * @property string|null $keyguard02
 * @property string|null $nameguard03
 * @property string|null $keyguard03
 * @property string|null $supervisorname
 * @property string|null $supervisorkey
 * @property string|null $nameautoriza
 * @property string|null $keyautoriza
 * @property string|null $containersealout
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereContainerseal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereContainersealout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyautoriza($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyguard01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyguard02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyguard03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyoperator01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyoperator02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereKeyoperator03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameautoriza($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameguard01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameguard02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameguard03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameoperator01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameoperator02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereNameoperator03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereSupervisorkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereSupervisorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitKey whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptUnitKey extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'receiptunitkeys';

    protected $fillable = [
        'centerid', 'warehouseid', 'folionumber', 'containerseal',
        'keyoperator01',
        'keyoperator02',
        'keyoperator03',
        'nameoperator01',
        'nameoperator02',
        'nameoperator03',
        'keyguard01',
        'keyguard02',
        'keyguard03',
        'nameguard01',
        'nameguard02',
        'nameguard03',
        'supervisorname',
        'supervisorkey',
        'nameautoriza',
        'keyautoriza',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return static::whereIn('receiptunitkeys.warehouseid', $warehouses)
            ->where('receiptunitkeys.centerid', $centers);
    }
}
