<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageMasterDetail extends Model
{
    public $timestamps = false;

    protected $table = 'storagemasterdetailevidence';

    protected $fillable = [
        'centerid','warehouseid','masterid','lpnid','scanuser','scandate',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageMasterDetail $model) {
                $model->scanuser = Auth::user()->username;
                $model->scandate = Carbon::now();
            });
        }
    }


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageMasterDetail::whereIn('storagemasterdetailevidence.warehouseid', $warehouses)
            ->whereIn('storagemasterdetailevidence.centerid', $centers);
    }

}
