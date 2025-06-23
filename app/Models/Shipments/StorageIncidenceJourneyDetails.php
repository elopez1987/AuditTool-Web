<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageIncidenceJourneyDetails extends Model
{

    protected $table = 'storageincidencejourneydetails';
    protected $primaryKey = 'folioid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'delivery',
        'partnumberid',
        'quantity',
        'unitmeasure',
        'razoncode',
        'comment',
        'downloaddate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusiddetail',
        'partnumberiderror'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];


//  TODO Revisar el estatus SN y cambiarlo por AB y borrar el registro de la base de datos despues de actualizar todos los registros en la tabla
    protected $attributes = [
        'statusiddetail' => 'SN',
        'schemaid' => 'EIJ',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageIncidenceJourneyDetails::whereIn('storageincidencejourneydetails.warehouseid', $warehouses)
            ->whereIn('storageincidencejourneydetails.centerid', $centers);
    }


    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusiddetail', 'statusid');
    }

    public function category()
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'razoncode', 'categoryincdetailid');
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'prtnum');
    }

    static function getDetailOfIncidenceForOrder($orderid)
    {
        return StorageIncidenceJourneyDetails::where('orderid', $orderid)->get();
    }


}
