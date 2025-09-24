<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string|null $fecha
 * @property int $barcodekey
 * @property string|null $shipmentid
 * @property int $folioplant
 * @property string|null $carriername
 * @property string|null $boxnumber
 * @property string|null $operatorname
 * @property string|null $tipo
 * @property string $starttravel
 * @property string|null $checkinplant
 * @property string|null $tmesaplant
 * @property string|null $rampaplant
 * @property string|null $startplant
 * @property string|null $finishplant
 * @property string|null $autorzaplant
 * @property string|null $checkoutplant
 * @property int|null $timeplant
 * @property int|null $foliowarehouse
 * @property string|null $checkinwarehouse
 * @property string|null $tmesawarehouse
 * @property string|null $rampawarehouse
 * @property string|null $startwarehouse
 * @property string|null $finishwarehouse
 * @property string|null $autorwarehouse
 * @property string|null $checkoutwarehouse
 * @property int|null $timewarehouse
 * @property string|null $closetravel
 * @property int|null $tiempovuelta
 * @property string $statusid
 * @property int|null $trayectoida
 * @property int|null $trayectovuelta
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel folio($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel foliowm($folionumberwm)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereAutorwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereAutorzaplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereBarcodekey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCarriername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCheckinplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCheckinwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCheckoutplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereCheckoutwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereClosetravel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereFinishplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereFinishwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereFolioplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereFoliowarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereRampaplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereRampawarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereStartplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereStarttravel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereStartwarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTiempovuelta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTimeplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTimewarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTmesaplant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTmesawarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTrayectoida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxTravel whereTrayectovuelta($value)
 * @mixin \Eloquent
 */
class StorageBoxTravel extends Model
{
    protected $table = 'vw_storageboxtravels';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageBoxTravel::whereIn('vw_storageboxtravels.centerid', $centers);
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('vw_storageboxtravels.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) !== '') {
            $query->where('vw_storageboxtravels.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('vw_storageboxtravels.folioplant', '=', $folionumber);
        }
    }

    public function scopeFoliowm($query, $folionumberwm)
    {
        if (trim($folionumberwm) !== '') {
            $query->where('vw_storageboxtravels.foliowarehouse', '=', $folionumberwm);
        }
    }
}
