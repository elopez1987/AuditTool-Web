<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptid
 * @property string $partnumberid
 * @property int $quantity
 * @property int $categoryid
 * @property int $categorydetailid
 * @property string $createuser
 * @property string|null $unitmeasureid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string $created_at
 * @property-read \App\Models\Receipt\StorageCategoryIncidence|null $categoryincidence
 * @property-read InventoryPartnumber|null $partnumber
 * @property-read \App\Models\Receipt\ReceiptStock $stock
 * @property-read \App\Models\Receipt\StorageCategoryIncidenceDetail|null $subcategoryincidence
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereCategorydetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereCategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereUnitmeasureid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptIncidenceStock whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptIncidenceStock extends Model
{
    public $timestamps = false;

    protected $table = 'receiptincidencestock';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'receiptid',
        'partnumberid',
        'quantity',
        'categoryid',
        'categorydetailid',
        'createuser',
        'created_at',
        'unitmeasureid',
    ];

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function stock()
    {
        return $this->belongsTo(ReceiptStock::class, 'receiptid', 'receiptid');
    }

    public function categoryincidence()
    {
        return $this->belongsTo(StorageCategoryIncidence::class, 'categoryid', 'categoryincid');
    }

    public function subcategoryincidence()
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'categorydetailid', 'categoryincdetailid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptIncidenceStock $model) {
                $model->createuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }
}
