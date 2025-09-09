<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptUnitKey extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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
