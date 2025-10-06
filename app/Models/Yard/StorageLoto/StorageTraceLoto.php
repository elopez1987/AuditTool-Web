<?php

namespace App\Models\Yard\StorageLoto;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageSealLoto;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property ReceiptUnit $folionumber
 * @property int $lotoid
 * @property int $eventid
 * @property int $userid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageReason $event
 * @property-read StorageSealLoto $loto
 * @property-read User $user
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereEventid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereLotoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTraceLoto whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageTraceLoto extends Model
{
    use HasUuids;
    use LogsActivity;

    protected $table = 'storagetraceloto';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folionumber',
        'lotoid',
        'eventid',
        'userid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageTraceLoto::whereIn('storagetraceloto.warehouseid', $warehouses)
            ->whereIn('storagetraceloto.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function folionumber(): BelongsTo
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber');
    }

    public function loto(): BelongsTo
    {
        return $this->belongsTo(StorageSealLoto::class, 'lotoid');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(StorageReason::class, 'eventid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public static function createNewEventToLoto($receiptunit, $loto, $event)
    {
        StorageTraceLoto::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'lotoid' => $loto->id,
            'eventid' => $event,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageTraceLoto $model) {
                $model->userid = Auth::user()->id;
                $model->created_at = Carbon::now();
            });
        }
    }
}
