<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

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
