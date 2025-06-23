<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageMasterEvidence extends Model
{
    public $timestamps = false;
    protected $table = 'storagemasterevidence';

    protected $fillable = [
        'centerid', 'warehouseid', 'masterid', 'createuser', 'createdate',
        'closeuser', 'closedate', 'statusid'
    ];


    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageMasterEvidence $model) {
                $model->createuser = Auth::user()->username;
                $model->createdate = Carbon::now();
            });
        }
    }


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageMasterEvidence::whereIn('storagemasterevidence.warehouseid', $warehouses)
            ->whereIn('storagemasterevidence.centerid', $centers);
    }

    public function scopeForMaster($query, $masterid)
    {
        if (trim($masterid) != '') {
            $query->where('masterid', $masterid);
        }
    }


}
