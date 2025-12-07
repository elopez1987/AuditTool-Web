<?php

namespace App\Models\Yard\Carriers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipmentcarrierscontact extends Model
{
    protected $table = 'shipmentcarrierscontacts';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'carrierid',
        'name',
        'email',
        'phone',
        'value01',
        'value02',
        'value03',
        'userid',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (Shipmentcarrierscontact $model) {
                $model->userid = \Auth::user()->id;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return static::whereIn('shipmentcarrierscontacts.centerid', $centers)
            ->whereIn('shipmentcarrierscontacts.warehouseid', $warehouses);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }
}
