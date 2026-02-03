<?php

namespace App\Models\Shipments\ShipmentTransport;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property int $carrierid
 * @property string|null $operatorname
 * @property string|null $drivernumber
 * @property string|null $phone
 * @property string|null $boxnumber
 * @property string|null $trucknumber
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property int|null $createuser
 * @property string|null $createdate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier $carrier
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereCreatedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereDrivernumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentTransport whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageShipmentTransport extends Model
{
    use LogsActivity;

    public $timestamps = false;

    //    Auxiliary01 == Ingreso de unidad en el módulo de patios (1 con transporte)
    //    Auxiliary02 == Datos de transporte registrado en sistema por tráfico o registro de QR
    //    Auxiliary03 == ''
    //    Auxiliary04 == 'Modelo ReceiptAsn o OrderDelivery'

    protected $table = 'shipmenttransport';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'carrierid',
        'operatorname',
        'drivernumber',
        'phone',
        'boxnumber',
        'trucknumber',
        'createuser',
        'createdate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'ecobox',
        'ecotruck',
        'has_data',
        'has_registered',
        'createddate',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageShipmentTransport $model) {
                $model->createuser = Auth::user()->id;
                $model->auxiliary05 = Uuid::uuid4()->toString();
                $model->createdate = Carbon::now();
            });
        }
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageShipmentTransport::whereIn('shipmenttransport.warehouseid', $warehouses)
            ->whereIn('shipmenttransport.centerid', $centers);
    }
}
