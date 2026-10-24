<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptStock extends Model
{
    use LogsActivity;

    protected $table = 'receiptstock';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'shipmentid',
        'receiptid',
        'partnumberid',
        'stockcategoryid',
        'expirecode',
        'expiredate',
        'productiondate',
        'quantitytrue',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    //    Relaciones entre modelos
    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }
}
