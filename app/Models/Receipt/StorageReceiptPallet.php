<?php

namespace App\Models\Receipt;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptid
 * @property int $partnumberid
 * @property int $quantity
 * @property int $damage
 * @property string $username
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereDamage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageReceiptPallet whereWarehouseid($value)
 * @mixin \Eloquent
 */
class StorageReceiptPallet extends Model
{
    use LogsActivity;

    protected $table = 'storagereceiptpallets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'warehouseid', 'receiptid', 'partnumberid', 'quantity',
        'damage', 'username',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageReceiptPallet::whereIn('storagereceiptpallets.warehouseid', $warehouses)
            ->whereIn('storagereceiptpallets.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageReceiptPallet $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
