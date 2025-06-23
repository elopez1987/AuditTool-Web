<?php

namespace App\Models\Inventory\FiscalInventory\Cards;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryCard extends Model
{
    protected $table = 'inventorycards';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'warehouseid', 'inventoryid', 'aisleid', 'cardid', 'locations',
        'startdate01', 'finishdate01', 'responsible01', 'unitmeasureid',
        'startdate02', 'finishdate02', 'responsible02', 'statusid',
        'keycount01','keycount02'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return InventoryCard::whereIn('inventorycards.centerid', $centers)
            ->whereIn('inventorycards.warehouseid', $warehouses);
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

    public function user01()
    {
        return $this->belongsTo(User::class, 'responsible01', 'username');
    }

    public function user02()
    {
        return $this->belongsTo(User::class, 'responsible02', 'username');
    }

    public function scopeAisle($query, $aisleid)
    {
        if (trim($aisleid) != "") {
            $query->where('inventorycards.aisleid', '=', $aisleid);
        }
    }

    public function scopeCard($query, $cardid)
    {
        if (trim($cardid) != "") {
            $query->where('inventorycards.cardid', '=', $cardid);
        }
    }

}
