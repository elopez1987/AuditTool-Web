<?php

namespace App\Models\Receipt;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageLocation;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $receiptid
 * @property int $locationid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property int $createuser
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read StorageLocation $location
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptStage whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ReceiptStage extends Model
{
    use LogsActivity;

    protected $table = 'receiptstage';

    protected $fillable = [
        'id',
        'centerid',
        'warehouseid',
        'receiptid',
        'locationid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_at' => 'datetime:d/m/Y H:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptStage::whereIn('receiptstage.warehouseid', $warehouses)
            ->whereIn('receiptstage.centerid', $centers);
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptStage $model) {
                $model->createuser = Auth::id();
            });
        }
    }
}
