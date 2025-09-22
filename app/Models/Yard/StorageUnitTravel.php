<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageUnitTravel extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected $table = 'storageunittravels';
    protected $primaryKey = 'id';

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
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'PL',
        'schemaid' => 'STB',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function closeFolioOfPlant($receiptunit): void
    {
        $travelplant = StorageUnitTravel::where('folioplant', $receiptunit->id)
            ->first(['id', 'checkoutplant']);
        if (! empty($travelplant)) {
            $travelplant->checkoutplant = now();
            $travelplant->save();
        }
    }

    public static function closeFolioOfWarehouse($receiptunit): void
    {
        $travelwarehouse = StorageUnitTravel::where('foliowarehouse', $receiptunit->id)
            ->first(['id', 'checkoutwarehouse']);
        if (! empty($travelwarehouse)) {
            $travelwarehouse->checkoutwarehouse = now();
            $travelwarehouse->save();
        }
    }
}
