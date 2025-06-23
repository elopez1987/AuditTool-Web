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

class StorageTraceLoto extends Model
{
    use HasUuids;

    protected $table = 'storagetraceloto';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folionumber',
        'lotoid',
        'eventid',
        'userid',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageTraceLoto $model) {
                $model->userid = Auth::user()->id;
                $model->created_at = Carbon::now();
            });
        }
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


    static function createNewEventToLoto($receiptunit, $loto, $event)
    {
        StorageTraceLoto::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'lotoid' => $loto->id,
            'eventid' => $event,
        ]);
    }

}
