<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class ReceiptContainer extends Model
{

    protected $table = 'receiptcontainers';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'warehouseid', 'receiptid', 'partnumberid', 'quantity', 'lpnid',
        'batch','productiondate'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return ReceiptContainer::whereIn('receiptcontainers.centerid', $centers)
            ->whereIn('receiptcontainers.warehouseid', $warehouses);
    }


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptContainer $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }


    static function getContainersForReceipt($receiptid)
    {
        return ReceiptContainer::controlAccessUser()
            ->where('receiptid', $receiptid)->get();
    }
}
