<?php

namespace App\Models\Evidence;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Evidence\StorageContainerTravelTrace
 *
 * @property string $centerid
 * @property string $warehouseid
 * @property string $lpnid
 * @property string $stationid
 * @property string $scanuser
 * @property string $scandate
 * @property int $id
 * @property-read SystemStatus|null $station
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereScandate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereScanuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereStationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageContainerTravelTrace whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageContainerTravelTrace extends Model
{
    public $timestamps = false;

    protected $table = 'storagecontainertraveltrace';

    protected $fillable = [
        'centerid', 'warehouseid', 'lpnid', 'scanuser', 'stationid', 'scandate',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageContainerTravelTrace $model) {
                $model->scanuser = Auth::user()->username;
                $model->scandate = Carbon::now();
            });
        }
    }

    public function lpn()
    {
        return $this->beforeQuery(StorageContainersTravel::class, 'lpnid', 'lpnid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    public function station()
    {
        return $this->belongsTo(SystemStatus::class, 'stationid', 'statusid');
    }
}
