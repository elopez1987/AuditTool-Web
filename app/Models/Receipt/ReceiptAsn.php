<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Observers\ReceiptAsnObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $workday
 * @property int|null $turnid
 * @property string|null $downloadpriority
 * @property int $providerid
 * @property int $carrierid
 * @property string $shipmentid
 * @property string|null $billoflading
 * @property string|null $oc
 * @property string|null $po
 * @property string|null $partnumberid
 * @property string|null $quantity
 * @property string|null $addasndate
 * @property string|null $arriveddate
 * @property string|null $scanstartdate
 * @property string|null $scanfinishdate
 * @property string|null $unloadstartdate
 * @property string|null $unloadfinishdate
 * @property string|null $verifystartdate
 * @property string|null $verifyfinishdate
 * @property string|null $cancellationdate
 * @property string|null $outputdate
 * @property string|null $goodissuedate
 * @property int $razonstatusid
 * @property int $stockcategoryid
 * @property int|null $folionumber
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $auxiliary06
 * @property string|null $auxiliary07
 * @property string|null $auxiliary08
 * @property string|null $auxiliary09
 * @property string|null $auxiliary10
 * @property string|null $comment
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier $carrier
 * @property-read SystemCenterDistribution $center
 * @property-read ReceiptUnit|null $folio
 * @property-read InventoryStockCategory $inventorystock
 * @property-read \App\Models\Receipt\ReceiptProvider $provider
 * @property-read StorageReason $reason
 * @property-read SystemStatus $status
 * @property-read StorageTurn|null $turn
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn delivery($delivery)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn factura($billoflading)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn folioNumber($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn oC($oc)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn provider($provierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn shipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAddasndate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereArriveddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereAuxiliary10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereBilloflading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereCancellationdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereDownloadpriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereGoodissuedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereOc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereOutputdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn wherePo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereProviderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereRazonstatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereScanfinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereScanstartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereStockcategoryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereUnloadfinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereUnloadstartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereVerifyfinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereVerifystartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn whereWorkday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsn workDay($workday)
 *
 * @mixin \Eloquent
 */
#[ObservedBy([ReceiptAsnObserver::class])]
class ReceiptAsn extends Model
{
    use LogsActivity;

    protected $table = 'receiptasn';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid', 'warehouseid', 'providerid', 'shipmentid', 'billoflading',
        'oc', 'chep', 'downloadpriority', 'arriveddate', 'operatorname', 'addasndate',
        'scanstartdate', 'scanfinishdate', 'cancellationdate', 'unloadstartdate', 'unloadfinishdate', 'goodissuedate',
        'carrierid', 'razonstatusid', 'folionumber', 'containerseal', 'stockcategoryid', 'turnid', 'auxiliary01',
        'auxiliary02', 'auxiliary03', 'auxiliary04', 'auxiliary05', 'auxiliary06', 'auxiliary07',
        'auxiliary08', 'auxiliary09', 'auxiliary10', 'statusid', 'totalcontainers', 'workday',
        'verifystartdate', 'verifyfinishdate', 'partnumberid', 'quantity', 'po', 'comment',
    ];

    protected $attributes = [
        'schemaid' => 'SRE',
    ];

    //    auxiliary01 = 'Planeador';
    //    auxiliary02 = 'Posteo SAP';
    //    auxiliary03 = 'Categoria de material';
    //    auxiliary04 = '';
    //    auxiliary05 = '';
    //    auxiliary06 = 'Numero de folio de planta para el monitor de transitos planta';
    //    auxiliary07 = '';
    //    auxiliary08 = 'Usuario analista de recibo';
    //    auxiliary09 = 'Usuario montacarguistas';
    //    auxiliary10 = 'Usuario verificador';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ReceiptAsn::whereIn('receiptasn.warehouseid', $warehouses)
            ->whereIn('receiptasn.centerid', $centers);
    }

    //    ------------------------------ Relaciones ------------------------------------------
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid');
    }

    public function provider()
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid');
    }

    public function reason()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function inventorystock()
    {
        return $this->belongsTo(InventoryStockCategory::class, 'stockcategoryid', 'id');
    }

    public function folio()
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id')
            ->withDefault([
                'id' => null,
                'entrydate' => null,
                'startdate' => null,
                'finishdate' => null,
            ]);
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid');
    }

    public function transport()
    {
        return $this->belongsTo(StorageShipmentTransport::class, 'id', 'orderid');
    }

    //   ---------------------------------- Filtros de consulta  --------------------------------------
    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('receiptasn.name', 'like', "%{$name}%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('receiptasn.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeDelivery($query, $delivery)
    {
        if (trim($delivery) != '') {
            $query->where('receiptasn.billoflading', '=', $delivery);
        }
    }

    public function scopeFolioNumber($query, $folionumber)
    {
        if (trim($folionumber) != '') {
            $query->where('receiptasn.folionumber', '=', $folionumber);
        }
    }

    public function scopeWorkDay($query, $workday)
    {
        if (trim($workday) != '') {
            $query->whereDate('receiptasn.addasndate', $workday);
        } else {
            $query->whereDate('receiptasn.addasndate', Carbon::now()->format('Y-m-d'));
        }
    }

    public function scopeOC($query, $oc)
    {
        if (trim($oc) != '') {
            $query->where('receiptasn.oc', '=', $oc);
        }
    }

    public function scopeFactura($query, $billoflading)
    {
        if (trim($billoflading) != '') {
            $query->where('billoflading', '=', $billoflading);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if ($statusid != '') {
            $query->where('receiptasn.statusid', '=', $statusid);
        }
    }

    public function scopeProvider($query, $provierid)
    {
        if ($provierid != '') {
            $query->where('receiptasn.providerid', '=', $provierid);
        }
    }

    public function scopeForWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != '') {
            $query->where('receiptasn.warehouseid', '=', $warehouseid);
        }
    }

    //    ---------------- Consultas del modelo ------------------------------------
    public static function searchReceiptForUnit($folioid)
    {
        return ReceiptAsn::controlAccessUser()->where('folionumber', $folioid)->first();
    }

    public static function searchReceiptInCheckIn($shipmentid)
    {
        return ReceiptAsn::controlAccessUser()->where('shipmentid', $shipmentid)
            ->whereIn('statusid', ['PG', 'TA'])
            ->whereNot('statusid', ['IG', 'CE'])->first();
    }

    //  -----------------------------------------  Funciones auxiliares -------------------------------------------------
    public static function updateStatusForZone($receiptunit)
    {
        $receiptAsn = ReceiptAsn::controlAccessUser()
            ->where('folionumber', '=', $receiptunit)
            ->select(['id', 'statusid'])->first();
        if (! empty($receiptAsn)) {
            $receiptAsn->statusid = 'ER';
            $receiptAsn->save();
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptAsn $model) {
                $model->auxiliary01 = Auth::user()->name;
            });
        }
    }
}
