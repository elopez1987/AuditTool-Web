<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;

class StorageUnitTravel extends Model
{
    protected $table = 'storageunittravels';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folioplant',
        'circuitopen',
        'checkinplant',
        'checkoutplant',
        'barcodekey',
        'foliowarehouse',
        'checkinwarehouse',
        'checkoutwarehouse',
        'circuitclose',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'PL',
        'schemaid' => 'STB',
    ];


   static  function closeFolioOfPlant($receiptunit): void
    {
        $travelplant = StorageUnitTravel::where('folioplant', $receiptunit->id)
            ->first(['id', 'checkoutplant']);
        if (!empty($travelplant)) {
            $travelplant->checkoutplant = now();
            $travelplant->save();
        }
    }

   static function closeFolioOfWarehouse($receiptunit): void
    {
        $travelwarehouse = StorageUnitTravel::where('foliowarehouse', $receiptunit->id)
            ->first(['id', 'checkoutwarehouse']);
        if (!empty($travelwarehouse)) {
            $travelwarehouse->checkoutwarehouse = now();
            $travelwarehouse->save();
        }
    }

}
