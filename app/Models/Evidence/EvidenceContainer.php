<?php

namespace App\Models\Evidence;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\EvidenceContainer
 *
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $shipmentid
 * @property int $partnumberid
 * @property float $quantity
 * @property string $lpnid
 * @property \Illuminate\Support\Carbon $adddatecheck
 * @property string $scanusercheck
 * @property string|null $filecheck
 * @property \Illuminate\Support\Carbon|null $adddateload
 * @property string|null $scanuserload
 * @property string|null $fileload
 * @property string|null $statusid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $schemaid
 * @property-read InventoryPartnumber $sku
 * @property-read SystemStatus|null $status
 * @property-read User|null $usercheck
 * @property-read User|null $userload
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer forLpn($lpnid)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer forSku($partnumberid)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer forWorkday($workday)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereAdddatecheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereAdddateload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereFilecheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereFileload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereScanusercheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereScanuserload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EvidenceContainer whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class EvidenceContainer extends Model
{
    public $timestamps = false;

    protected $table = 'storageevidencecontainers';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'partnumberid',
        'quantity',
        'lpnid',
        'adddatecheck',
        'scanusercheck',
        'filecheck',
        'adddateload',
        'scanuserload',
        'fileload',
        'statusid',
        'value01',
        'value02',
        'value03',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'FS',
        'schemaid' => 'RSC',
    ];

    protected $casts = [
        'adddatecheck' => 'datetime:d/m/Y H:s',
        'adddateload' => 'datetime:d/m/Y H:s',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return EvidenceContainer::whereIn('storageevidencecontainers.warehouseid', $warehouses)
            ->whereIn('storageevidencecontainers.centerid', $centers);
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function usercheck()
    {
        return $this->belongsTo(User::class, 'scanusercheck', 'username');
    }

    public function userload()
    {
        return $this->belongsTo(User::class, 'scanuserload', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    //    Scopes
    public function scopeForWorkday($query, $workday)
    {
        if (trim($workday) != '') {
            $query->where('storageevidenceoperations.workday', '=', $workday);
        }
    }

    public function scopeForLpn($query, $lpnid)
    {
        if (trim($lpnid) != '') {
            $query->where('storageevidencecontainers.lpnid', $lpnid);
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('storageevidenceoperations.shipmentid', $shipmentid);
        }
    }

    public function scopeForSku($query, $partnumberid)
    {
        if (trim($partnumberid) != '') {
            $sku = InventoryPartnumber::controlAccessUser()
                ->where('prtnum', $partnumberid)->first();
            $query->where('storageevidencecontainers.partnumberid', $sku->id);
        }

    }
}
