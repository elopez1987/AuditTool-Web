<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class StorageEvidences extends Model
{

    protected $table = 'storageevidences';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'modelid',
        'modeltype',
        'path',
        'username',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageEvidences $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageEvidences::whereIn('storageevidences.centerid', $centers);
    }

    public static function createNewWvidence($model, $path, $modeltype, $modelid)
    {
        StorageEvidences::create([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'modelid' => $modelid,
            'modeltype' => $modeltype,
            'path' => $path,
        ]);
    }

}
