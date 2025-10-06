<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptid
 * @property string $partnumberid
 * @property float $quantity
 * @property string $lpnid
 * @property string|null $batch
 * @property string|null $productiondate
 * @property string|null $expiredate
 * @property string|null $codeplant
 * @property string|null $eanbox
 * @property string|null $eanpz
 * @property string|null $photo
 * @property string $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read InventoryPartnumber|null $partnumber
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereCodeplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereEanbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereEanpz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereExpiredate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereProductiondate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptContainer whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptContainer extends Model
{
    protected $table = 'receiptcontainers';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'warehouseid', 'receiptid', 'partnumberid', 'quantity', 'lpnid',
        'batch', 'productiondate', 'codeplant', 'eanbox', 'eanpz', 'photo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return ReceiptContainer::whereIn('receiptcontainers.centerid', $centers)
            ->whereIn('receiptcontainers.warehouseid', $warehouses);
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public static function getContainersForReceipt($receiptid)
    {
        return ReceiptContainer::controlAccessUser()->with('partnumber')
            ->where('receiptid', $receiptid)->get();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptContainer $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
