<?php

namespace App\Models\Inventory\FiscalInventory\Dron;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class StorageCountDronHead extends Model
{
    protected $table = 'storagecountdronhead';


    protected $fillable = [
        'centerid', 'warehouse', 'countid', 'countdate', 'countrack', 'countflyer',
        'value01', 'value02', 'value03', 'value04', 'value05', 'username'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'countdate' => 'datetime:d/m/Y H:s',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageCountDronHead $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCountDronHead::whereIn('storagecountdronhead.centerid', $centers);
    }

    public function scopeCountID($query, $countid)
    {
        if (trim($countid) != '') {
            $query->where('storagecountdronhead.countid', $countid);
        }
    }

    public function scopeRack($query, $rack)
    {
        if (trim($rack) != '') {
            $query->where('countrack', $rack);
        }
    }

    public function scopeWorkday($query, $workday)
    {
        if (trim($workday) != '') {
            $query->whereDate('countdate', $workday);
        }
    }

}
