<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $countid
 * @property int $folionumber
 * @property string $barcode
 * @property string $boxnumber
 * @property int $zoneid
 * @property int $locationid
 * @property int|null $newzoneid
 * @property int|null $newlocationid
 * @property string|null $isupdate
 * @property string $countprogramdate
 * @property int|null $userid
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution|null $center
 * @property-read \App\Models\Yard\ReceiptUnit $folio
 * @property-read \App\Models\Yard\InventoryBox $inventory
 * @property-read \App\Models\Yard\StorageLocation|null $locationnew
 * @property-read \App\Models\Yard\StorageLocation|null $locationold
 * @property-read SystemStatus|null $status
 * @property-read User|null $user
 * @property-read StorageZone|null $zonenew
 * @property-read StorageZone|null $zoneold
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereCountid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereCountprogramdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereIsupdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereNewlocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereNewzoneid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountStock whereZoneid($value)
 *
 * @mixin \Eloquent
 */
class InventoryCountStock extends Model
{
    use LogsActivity;

    protected $table = 'inventorycountstock';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'countid',
        'folionumber',
        'barcode',
        'boxnumber',
        'zoneid',
        'locationid',
        'newzoneid',
        'newlocationid',
        'countprogramdate',
        'statusid',
        'userid',
        'isupdate',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return InventoryCountStock::whereIn('inventorycountstock.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function locationold()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function locationnew()
    {
        return $this->belongsTo(StorageLocation::class, 'newlocationid', 'id');
    }

    public function zoneold()
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id');
    }

    public function zonenew()
    {
        return $this->belongsTo(StorageZone::class, 'newzoneid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id')->withTrashed()->select('id', 'username', 'name');
    }

    public function folio()
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id');
    }

    public function inventory()
    {
        return $this->belongsTo(InventoryBox::class, 'countid', 'id');
    }

    public static function createDetailCount($inventory, $unit)
    {
        InventoryCountStock::create([
            'centerid' => $inventory->centerid,
            'countid' => $inventory->id,
            'folionumber' => $unit->id,
            'barcode' => $unit->barcode,
            'boxnumber' => $unit->boxnumber,
            'zoneid' => $unit->zoneid,
            'locationid' => $unit->locationid,
            'statusid' => $unit->statusid,
            'isupdate' => 'P',
            'countprogramdate' => Carbon::now()->toDateString(),
        ]);
    }

    public static function searchForFolioNumber($id, $inventory)
    {
        return InventoryCountStock::where('folionumber', '=', $id)
            ->where('countid', $inventory->id)->first();
    }
}
