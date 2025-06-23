<?php

namespace App\Models\Inventory\FiscalInventory\Locations;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InventoryLocationLog extends Model
{
    public $timestamps = false;
    protected $table = 'inventorylocationlogs';

    protected $fillable = [
      'inventoryid','cardid','locationid','createuser','created_at','description'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (InventoryLocationLog $model) {
                $model->createuser = \Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }

}
