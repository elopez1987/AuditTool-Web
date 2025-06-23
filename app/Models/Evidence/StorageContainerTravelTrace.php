<?php

namespace App\Models\Evidence;


use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageContainerTravelTrace extends Model
{
    public $timestamps = false;

    protected $table = 'storagecontainertraveltrace';

    protected $fillable = [
        'centerid', 'warehouseid', 'lpnid', 'scanuser', 'stationid', 'scandate'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
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
