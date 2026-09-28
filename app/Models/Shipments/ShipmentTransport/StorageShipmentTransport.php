<?php

namespace App\Models\Shipments\ShipmentTransport;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

//    Auxiliary01 ==
//    Auxiliary02 ==
//    Auxiliary03 ==
//    Auxiliary04 == 'Modelo ReceiptAsn o OrderDelivery'
//    Auxiliary05 == 'UUId de acceso '

class StorageShipmentTransport extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

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

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageShipmentTransport $model) {
                $model->createuser = Auth::id();
                $model->auxiliary05 = Uuid::uuid4()->toString();
                $model->createdate = Carbon::now();
            });
            self::updating(function (StorageShipmentTransport $model) {
                $model->createuser = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $username = Auth::user()->username;

        return StorageShipmentTransport::query()
            ->whereIn(
                'shipmenttransport.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            )
            ->whereIn(
                'shipmenttransport.warehouseid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('warehouseid'),
            );
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }
}
