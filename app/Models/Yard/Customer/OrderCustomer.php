<?php

namespace App\Models\Yard\Customer;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static whereStatusid(string $string)
 */
class OrderCustomer extends Model
{
    protected $table = 'ordercustomers';
    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'city',
        'customerid',
        'shiptoid',
        'timetransfer',
        'timebulkassembly',
        'timearmedpick',
        'timecheck',
        'timeload',
        'timebilling',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];

    public function getCity($value)
    {
        return strtoupper($value);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'shiptoid' => 'string',
        'configurationflag' => 'array',
    ];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return OrderCustomer::whereIn('ordercustomers.centerid', $centers)
            ->whereIn('ordercustomers.warehouseid', $warehouses);
    }


    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('ordercustomers.name', 'like', "%$name%");
        }
    }

    public function scopeShipto($query, $shiptoid)
    {
        if (trim($shiptoid) != "") {
            $query->where('ordercustomers.shiptoid', 'like', "%$shiptoid%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != "") {
            $query->where('ordercustomers.warehouseid', '=', $warehouseid);
        }

    }


    

}
