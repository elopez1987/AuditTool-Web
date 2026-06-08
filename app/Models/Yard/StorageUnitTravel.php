<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $barcodekey
 * @property int $folioplant
 * @property string $circuitopen
 * @property string $checkinplant
 * @property string|null $checkoutplant
 * @property int|null $foliowarehouse
 * @property string|null $checkinwarehouse
 * @property string|null $checkoutwarehouse
 * @property string|null $circuitclose
 * @property string $schemaid
 * @property string $statusid
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereBarcodekey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCheckinplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCheckinwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCheckoutplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCheckoutwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCircuitclose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereCircuitopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereFolioplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereFoliowarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageUnitTravel whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
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
