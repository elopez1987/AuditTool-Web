<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageDoorStage extends Model
{
    public $timestamps = false;
    protected $table = 'storagedoorstages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'doorid',
        'stageid',
        'username',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageDoorStage $model) {
                $model->username = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageDoorStage::whereIn('storagedoorstages.centerid', $centers);
    }

}
