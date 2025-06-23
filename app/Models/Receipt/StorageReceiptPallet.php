<?php

namespace App\Models\Receipt;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageReceiptPallet extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
    
    protected $table = 'storagereceiptpallets';
    protected $primaryKey = 'id';


    protected $fillable = [
        'centerid', 'warehouseid', 'receiptid', 'partnumberid', 'quantity',
        'damage', 'username'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageReceiptPallet $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageReceiptPallet::whereIn('storagereceiptpallets.warehouseid', $warehouses)
            ->whereIn('storagereceiptpallets.centerid', $centers);
    }

}
