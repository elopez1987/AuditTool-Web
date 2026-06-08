<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptid
 * @property string $shipmentid
 * @property string $partnumberid
 * @property int $category01id
 * @property int $category02id
 * @property int $stockcategoryid
 * @property string|null $expirecode
 * @property string|null $expiredate
 * @property string|null $productiondate
 * @property float|null $quantitytrue
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read InventoryPartNumberCategory01|null $category01
 * @property-read InventoryPartNumberCategory02|null $category02
 * @property-read SystemCenterDistribution $center
 * @property-read InventoryPartnumber|null $partnumber
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereCategory01id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereCategory02id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereExpirecode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereExpiredate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereProductiondate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereQuantitytrue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereStockcategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStock whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptStock extends Model
{
    use LogsActivity;

    protected $table = 'receiptstock';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'receiptid',
        'partnumberid',
        'stockcategoryid',
        'expirecode',
        'expiredate',
        'productiondate',
        'quantitytrue',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return static::whereIn('receiptstock.warehouseid', $warehouses)
            ->whereIn('receiptstock.centerid', $centers);
    }

    //    Relaciones entre modelos
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }
}
