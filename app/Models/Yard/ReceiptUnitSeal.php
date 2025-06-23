<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;

class ReceiptUnitSeal extends Model
{
    public $timestamps = true;
    protected $table = 'receiptunitseals';

    protected $fillable = [
        'centerid', 'warehouseid', 'folionumber', 'seal01', 'seal02', 'seal03', 'value01', 'value02', 'value3', 'createuser'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptUnitSeal $model) {
                $model->createuser = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnitSeal::whereIn('receiptunitseals.warehouseid', $warehouses)
            ->whereIn('receiptunitseals.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }


    static function createNewUnitSeal(ReceiptUnit $receiptunit,$type)
    {
        ReceiptUnitSeal::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'seal01' => $receiptunit->containerseal,
            'value01' => $type
        ]);
    }

}
