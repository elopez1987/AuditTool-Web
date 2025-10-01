<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int|null $schedulleid
 * @property int $id
 * @property string|null $workday
 * @property string|null $schemasecuenceid
 * @property string|null $secuenceid
 * @property int|null $folioid
 * @property int|null $quantity
 * @property int|null $assigneduser
 * @property string $schemaid
 * @property string|null $statusid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereAssigneduser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereSchedulleid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereSchemasecuenceid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereSecuenceid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleInverseDetail whereWorkday($value)
 * @mixin \Eloquent
 */
class ScheduleInverseDetail extends Model
{
    public $timestamps = false;
    protected $table = 'scheduleinversedetail';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'schedulleid',
        'workday',
        'secuenceid',
        'folioid',
        'quantity',
        'assigneduser',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'DIS',
        'schemaid' => 'SKD',
        'schemasecuenceid' => 'KLH',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ScheduleInverseDetail::whereIn('scheduleinversedetail.warehouseid', $warehouses)
            ->whereIn('scheduleinversedetail.centerid', $centers);
    }
}
