<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ReceiptUnitKey extends Model
{
    public $timestamps = false;
    protected $table = 'receiptunitkeys';

    protected $fillable = [
        'centerid', 'warehouseid', 'folionumber', 'containerseal',
        'keyoperator01',
        'keyoperator02',
        'keyoperator03',
        'nameoperator01',
        'nameoperator02',
        'nameoperator03',
        'keyguard01',
        'keyguard02',
        'keyguard03',
        'nameguard01',
        'nameguard02',
        'nameguard03',
        'supervisorname',
        'supervisorkey',
        'nameautoriza',
        'keyautoriza',

    ];


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnitKey::whereIn('receiptunitkeys.warehouseid', $warehouses)
            ->where('receiptunitkeys.centerid',$centers);
    }

}
