<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


//Auxiliary01 = Codigo de barra del tracto
//Auxiliary02 = Número de folio del cual fue generado en el proceso de Reautilizar folio
//Auxiliary03 = Tipo de entrada en los modulos de registro (TR = Transporte, QR = Acceso por QR, ST = Registro Estandar)
//Auxiliary04 =


class ReceiptUnit extends Model
{

    public $timestamps = false;
    protected $table = 'receiptunits';


    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'shipmentid',
        'carrierid',
        'truckid',
        'containerseal',
        'operatorname',
        'entrydate',
        'startdate',
        'finishdate',
        'autorizaoutputdate',
        'outputdate',
        'locationid',
        'unittypeid',
        'trucknumber',
        'boxnumber',
        'econumber',
        'type',
        'zoneid',
        'phone',
        'name',
        'razonstatusid',
        'unitstatusid',
        'drivernumber',
        'truckcategoryid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'createuser',
        'updateuser',
        'statusid',
        'keydate',
        'foliotruck',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
        'statusid' => 'ES',
    ];

    protected $casts = [
//        'entrydate' => 'datetime:d/m/Y H:i',
//        'deliverdate' => 'datetime:d/m/Y H:i',
//        'keydate' => 'datetime:d/m/Y H:i'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptUnit $model) {
                $model->createuser = Auth::id();
                $model->entrydate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnit::whereIn('receiptunits.warehouseid', $warehouses)
            ->whereIn('receiptunits.centerid', $centers);
    }



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

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')->withTrashed();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id')->withTrashed();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id')->withTrashed();
    }

    public function truck()
    {
        return $this->belongsTo(ShipmentTruck::class, 'truckid')->withTrashed();
    }

    public function unitstatus()
    {
        return $this->belongsTo(StorageUnitStatus::class, 'unitstatusid');
    }

    public function truckcategory()
    {
        return $this->belongsTo(ShipmentTruckCategory::class, 'truckcategoryid');
    }

    public function reason()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function folioclient()
    {
        return $this->belongsTo(StorageFolioClient::class, 'id', 'folionumber');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public function userclose()
    {
        return $this->belongsTo(User::class, 'updateuser', 'id')->withTrashed();
    }
    public function key()
    {
        return $this->belongsTo(ReceiptUnitKey::class,'id','folionumber');
    }

    public function tiket()
    {
        return $this->belongsTo(ReceiptUnitTicket::class,'id','folionumber');
    }

    public function unitdata()
    {
        return $this->belongsTo(ReceiptUnitData::class,'id','folionumber');
    }

    public function unittype()
    {
        return $this->belongsTo(StorageUnitType::class, 'unittypeid', 'id');
    }

    public function tracto()
    {
        return $this->belongsTo(ReceiptTruck::class, 'foliotruck','id');
    }
    
//    --Scopes para filtros de tabla
    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != "") {
            $query->where('receiptunits.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != "") {
            $query->where('receiptunits.barcode', 'like', "%$barcode%");
        }
    }

    public function scopeLocation($query, $location)
    {
        if (trim($location) != "") {
            $query->where('sl.name', 'like', "%$location%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != "") {
            $query->where('receiptunits.carrierid', '=', $carrierid);
        }
    }

    public function scopeCenter($query, $centerid)
    {
        if (trim($centerid) != "") {
            $query->where('receiptunits.centerid', '=', $centerid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if ($warehouseid != "") {
            $query->where('receiptunits.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeRason($query, $rasonid)
    {
        if (trim($rasonid) != "") {
            $query->where('receiptunits.razonstatusid', '=', $rasonid);
        }
    }

    public function scopeRazonCode($query, $razon)
    {
        if (!empty($razon)) {
            $query->whereIn('receiptunits.razonstatusid', $razon);
        }
    }


    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('receiptunits.boxnumber', 'like', "%$boxnumber%");
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) != '') {
            $query->where('receiptunits.econumber', 'like', "%$econumber%");
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) != '') {
            $query->where('receiptunits.trucknumber', 'like', "%$trucknumber%");
        }
    }

    public function scopeBox($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('receiptunits.boxnumber', '=', $boxnumber);
        }
    }

    public function scopeCode($query, $barcode)
    {
        if (trim($barcode) != "") {
            $query->where('receiptunits.barcode', '=', $barcode);
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('receiptunits.shipmentid', 'like', "%$shipmentid%");
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('receiptunits.shipmentid', '=', $shipmentid);
        }
    }


    public function scopeFilterForRangeDate($query, $startDate, $endDate)
    {
        if ($startDate != '' && $endDate != '') {
            if ($startDate == $endDate) {
                $dataIn = Carbon::parse($startDate)->startOfDay();
                $dateOut = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('receiptunits.entrydate', [$dataIn, $dateOut]);
            } else {
                $query->whereBetween('receiptunits.entrydate', [$startDate, $endDate]);
            }
        }
    }

    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            $query->whereIn('receiptunits.statusid', $status);
        }
    }

    public function scopeCarrierArray($query, $carrier)
    {
        if (!empty($carrier)) {
            $query->whereIn('receiptunits.carrierid', $carrier);
        }
    }

//----------------------------------------------------- Filtros del modelo -----------------------
    static function searchForBarcode($barcode)
    {
        return ReceiptUnit::controlAccessUser()->whereBarcode($barcode)->whereNotIn('statusid', ['TR'])->first();
    }

    static function searchUnitForArrayNotStatus($barcode, $status)
    {
        return ReceiptUnit::controlAccessUser()->whereBarcode($barcode)->whereNotIn('statusid', $status)->first();
    }

    static function searchForBarcodeTruck($barcodetruck)
    {
        return ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])->where('foliotruck', $barcodetruck)->first();
    }

    static function searchForId($id)
    {
        return ReceiptUnit::controlAccessUser()->where('receiptunits.id', $id)->whereNotIn('statusid', ['TR'])->first();
    }

    static function searchForBoxNumber($boxnumber)
    {
        return ReceiptUnit::controlAccessUser()->where('boxnumber', $boxnumber)->whereNotIn('statusid', ['TR'])->first();
    }

    static function getCountUnit($centerid)
    {
        return ReceiptUnit::whereCenterid($centerid)->whereNotIn('statusid', ['TR'])->count();
    }

    static function searchUnitForLocation($locationid)
    {
        return ReceiptUnit::controlAccessUser()->where('locationid', $locationid)->whereNotIn('statusid', ['TR'])->first();
    }

//------------------------------------------------------ Funciones auxiliares ---------------------------------------------
    static function updateUnitForRefuse($folio)
    {
        $unit = ReceiptUnit::controlAccessUser()->where('id', '=', $folio)
            ->whereNotIn('statusid', ['TR'])->first();
        $unit->autorizaoutputdate = now();
        $unit->statusid = 'OU';
        $unit->shipmentid = '';
        $unit->unitstatusid = '3';
        $unit->save();
        StorageUnitTrace::createTraceUnit($unit, '23');
        return $unit;
    }





//------------------------------------------------------ Funciones auxiliares ---------------------------------------------



    static function updateUnitForAssingTruck($unit)
    {
        $unit->operatorname = "";
        $unit->phone = "";
        $unit->drivernumber = "";
        $unit->trucknumber = "";
        $unit->auxiliary01 = "";
        $unit->save();
    }

    static function updateStatusForFinishProcess($folionumber)
    {
        $folionumber->finishdate = now();
        $folionumber->statusid = 'FI';
        $folionumber->unitstatusid = $folionumber->razonstatusid == 1 ? 2 : 1;
        $folionumber->save();
    }

    public function updateLocation($receiptunit, $zoneid, $locationid)
    {
        $receiptunit->zoneid = $zoneid;
        $receiptunit->locationid = $locationid;
        $receiptunit->save();
    }

    static function moveUnitToLostForInventory($receiptunit, $location)
    {
        $receiptunit->zoneid = $location->value02;
        $receiptunit->locationid = $location->value03;
        $receiptunit->save();
    }

    static function updateUnitForInventory($receiptunit, $request)
    {
        $receiptunit->zoneid = $request['zoneid'];
        $receiptunit->locationid = $request['locationid'];
        $receiptunit->unitstatusid = $request['unitstatusid'];
        $receiptunit->save();
    }

    static function closeReceiptUnit(ReceiptUnit $receiptUnit, Request $request)
    {
        $shipment = $request['shipment'] ?? 0;
        $seal = $request['seal'] ?? 0;
        $receiptUnit->containerseal = $seal;
        $receiptUnit->shipmentid = $shipment;
        $receiptUnit->outputdate = Carbon::now();
        $receiptUnit->updateuser = Auth::user()->id;
        $receiptUnit->statusid = 'TR';
        $receiptUnit->save();
    }


}
