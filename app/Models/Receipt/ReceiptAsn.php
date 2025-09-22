<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
        'verifystartdate', 'verifyfinishdate','partnumberid','quantity','po','comment',
    ];

    protected $attributes = [
        'schemaid' => 'SRE',
    ];

//    auxiliary01 = 'Planeador';
//    auxiliary02 = 'Posteo SAP';
//    auxiliary03 = 'Categoria de material';
//    auxiliary04 =
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
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid');
    }

    //   ---------------------------------- Filtros de consulta  --------------------------------------
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('receiptasn.name', 'like', "%{$name}%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('receiptasn.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeDelivery($query, $delivery)
    {
        if (trim($delivery) !== '') {
            $query->where('receiptasn.billoflading', '=', $delivery);
        }
    }

    public function scopeFolioNumber($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('receiptasn.folionumber', '=', $folionumber);
        }
    }

    public function scopeWorkDay($query, $workday)
    {
        if (trim($workday) !== '') {
            $query->whereDate('receiptasn.addasndate', $workday);
        } else {
            $query->whereDate('receiptasn.addasndate', Carbon::now()->format('Y-m-d'));
        }
    }

    public function scopeOC($query, $oc)
    {
        if (trim($oc) !== '') {
            $query->where('receiptasn.oc', '=', $oc);
        }
    }

    public function scopeFactura($query, $billoflading)
    {
        if (trim($billoflading) !== '') {
            $query->where('billoflading', '=', $billoflading);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if ($statusid !== '') {
            $query->where('receiptasn.statusid', '=', $statusid);
        }
    }

    public function scopeProvider($query, $provierid)
    {
        if ($provierid !== '') {
            $query->where('receiptasn.providerid', '=', $provierid);
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
        $receiptAsn = ReceiptAsn::controlAccessUser()->where('folionumber', '=', $receiptunit)->select(['id', 'statusid'])->first();
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
