<?php

namespace App\Models\Inventory\FiscalInventory\Dron;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LocactionDronEvidence extends Model
{
    public $timestamps = false;

    protected $table = 'inventorylocationsdronevidence';

    protected $fillable = [
        'inventoryid',
        'locationid',
        'cardid',
        'createuser',
        'created_at',
        'path',
    ];


    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
//        'downloaddate' => 'datetime:d/m/Y H:s',
//        'workday' => 'datetime:d/m/Y'
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (LocactionDronEvidence $model) {
                $model->created_at = Carbon::now();
            });
        }
    }

}
