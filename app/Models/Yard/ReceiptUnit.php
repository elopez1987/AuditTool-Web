<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Scopes\Yard\StorageUnitTypeScope;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

// Auxiliary01 = Nombre del cliente o proveedor
// Auxiliary02 = Número de folio del cual fue generado en el proceso de Reutilizar folio
// Auxiliary03 = Tipo de entrada en los modulos de registro (TR = Transporte, QR = Acceso por QR, ST = Registro Estandar)
// Auxiliary04 =

class ReceiptUnit extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'receiptunits';

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
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'RUS',
        'statusid' => 'ES',
    ];

    // ################################################## Inicio  de columas calculadas ######################################

    protected $appends = [
        'staytime',
        'processtime',
    ];

    public function getStaytimeAttribute(): string
    {
        if (empty($this->entrydate)) {
            return 'N/A';
        }

        return $this->formatDuration(Carbon::parse($this->entrydate), Carbon::now());
    }

    public function getProcesstimeAttribute(): string
    {
        if (empty($this->startdate) || empty($this->finishdate)) {
            return '';
        }

        return $this->formatDuration(Carbon::parse($this->startdate), Carbon::parse($this->finishdate));
    }

    private function formatDuration(Carbon $start, Carbon $end): string
    {
        $diff = $start->diff($end);

        $units = [
            'día' => $diff->days,
            'hora' => $diff->h,
            'minuto' => $diff->i,
        ];

        $parts = [];

        foreach ($units as $label => $value) {
            if ($value > 0) {
                $parts[] = $value . ' ' . $this->pluralize($label, $value);
            }
        }

        if (empty($parts)) {
            $parts[] = '0 minutos';
        }

        return implode(', ', $parts);
    }

    private function pluralize(string $label, int $value): string
    {
        if ($value === 1) {
            return $label;
        }

        return match ($label) {
            'día' => 'días',
            default => $label . 's',
        };
    }

    // ################################################## Fin de columas calculadas ######################################

    public static function controlAccessUser()
    {
        $access = SecurityAccessWarehouse::AccessScope();

        return ReceiptUnit::whereIn('receiptunits.warehouseid', $access['warehouses'])
            ->whereIn('receiptunits.centerid', $access['centers']);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptUnit $model) {
                $model->createuser = Auth::id();
                $model->entrydate = Carbon::now();
            });
        }
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new StorageUnitTypeScope);
    }

    // ################################################## Relaciones entre modelos ######################################

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')
            ->withTrashed()->withDefault([
                'name' => 'SIN DATOS',
            ]);
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
        return $this->belongsTo(ShipmentTruck::class, 'truckid', 'id');
    }

    public function unitstatus()
    {
        return $this->belongsTo(StorageUnitStatus::class, 'unitstatusid', 'id');
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

    public function unittype()
    {
        return $this->belongsTo(StorageUnitType::class, 'unittypeid', 'id');
    }

    public function tracto()
    {
        return $this->belongsTo(ReceiptTruck::class, 'foliotruck', 'id')->withDefault([
            'trucknumber' => '00000',
            'operatorname' => 'SIN OPERADOR',
            'phone' => '00000',
            'id' => null,
        ]);
    }

    public function key()
    {
        return $this->belongsTo(ReceiptUnitKey::class, 'id', 'folionumber')
            ->withDefault([
                'id' => null,
                'folionumber' => '',
                'keyoperator01' => '',
                'keyoperator02' => '',
                'keyoperator03' => '',
                'nameoperator01' => '',
                'nameoperator02' => '',
                'nameoperator03' => '',
            ]);
    }

    public function tiket()
    {
        return $this->belongsTo(ReceiptUnitTicket::class, 'id', 'folionumber')
            ->withDefault([
                'id' => null,
                'ticket' => '',
                'weightin' => '',
                'weightout' => '',
            ]);
    }

    public function unitdata()
    {
        return $this->belongsTo(ReceiptUnitData::class, 'id', 'folionumber');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'id', 'folionumber');
    }

    // ################################################## Fin de relaciones entre modelos ######################################

    // ################################################## Inicio de Scopes para filtros  ######################################
    public function scopeFolio($query, $folionumber)
    {
        if ($folionumber != '') {
            $query->where('receiptunits.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if ($barcode != '') {
            $query->where('receiptunits.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeLocation($query, $location)
    {
        // OJO: antes filtraba por 'sl.name' sin que exista ningún join con ese
        // alias, lo que generaba un error SQL en cuanto se usaba este filtro.
        // whereHas reutiliza la relación 'location' (ya definida en el modelo)
        // y genera un WHERE EXISTS indexado por locationid, sin necesitar joins.
        if ($location != '') {
            $query->whereHas('location', function ($q) use ($location) {
                $q->where('name', 'like', "%{$location}%");
            });
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (is_array($carrierid) && ! empty($carrierid)) {
            $query->whereIn('receiptunits.carrierid', $carrierid);
        } elseif ($carrierid != '') {
            $query->where('receiptunits.carrierid', '=', $carrierid);
        }
    }

    public function scopeCarrierArray($query, $carrier)
    {
        if (! empty($carrier)) {
            $query->whereIn('receiptunits.carrierid', $carrier);
        }
    }

    public function scopeCenter($query, $centerid)
    {
        if ($centerid != '') {
            $query->where('receiptunits.centerid', '=', $centerid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if ($warehouseid != '') {
            $query->where('receiptunits.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeRason($query, $rasonid)
    {
        if (is_array($rasonid) && ! empty($rasonid)) {
            $query->whereIn('receiptunits.razonstatusid', $rasonid);
        } elseif ($rasonid != '') {
            $query->where('receiptunits.razonstatusid', '=', $rasonid);
        }
    }

    public function scopeUnitType($query, $unittypeid)
    {
        if (is_array($unittypeid) && ! empty($unittypeid)) {
            $query->whereIn('receiptunits.unittypeid', $unittypeid);
        } elseif (trim($unittypeid) != '') {
            $query->where('receiptunits.unittypeid', '=', $unittypeid);
        }
    }

    public function scopeRazonCode($query, $razon)
    {
        if (! empty($razon)) {
            $query->whereIn('receiptunits.razonstatusid', $razon);
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if ($boxnumber != '') {
            $query->where('receiptunits.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if ($econumber != '') {
            $query->where('receiptunits.econumber', 'like', "%{$econumber}%");
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if ($trucknumber != '') {
            $query->where('receiptunits.trucknumber', 'like', "%{$trucknumber}%");
        }
    }

    public function scopeBox($query, $boxnumber)
    {
        if ($boxnumber != '') {
            $query->where('receiptunits.boxnumber', '=', $boxnumber);
        }
    }

    public function scopeCode($query, $barcode)
    {
        if ($barcode != '') {
            $query->where('receiptunits.barcode', '=', $barcode);
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if ($shipmentid != '') {
            $query->where('receiptunits.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if ($shipmentid != '') {
            $query->where('receiptunits.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeUnitStatus($query, $unitstatusid)
    {
        if ($unitstatusid != '') {
            $query->where('receiptunits.unitstatusid', '=', $unitstatusid);
        }
    }

    public function scopeFilterForRangeDate($query, $startDate, $endDate)
    {
        if ($startDate !== '' && $endDate !== '') {
            if ($startDate === $endDate) {
                $dataIn = Carbon::parse($startDate)->startOfDay();
                $dateOut = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('receiptunits.entrydate', [$dataIn, $dateOut]);
            } else {
                $query->whereBetween('receiptunits.entrydate', [$startDate, $endDate]);
            }
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (is_array($statusid) && ! empty($statusid)) {
            $query->whereIn('receiptunits.statusid', $statusid);
        } elseif ($statusid != '') {
            $query->where('receiptunits.statusid', '=', $statusid);
        }
    }

    // ################################################## Fin de Scopes  ######################################

    // ----------------------------------------------------- Filtros del modelo -----------------------
    public static function searchForBarcode($barcode)
    {
        return ReceiptUnit::controlAccessUser()
            ->whereBarcode($barcode)
            ->whereNotIn('receiptunits.statusid', ['TR'])->lockForUpdate()->first();
    }

    public static function searchUnitForArrayNotStatus($barcode, $status)
    {
        return ReceiptUnit::controlAccessUser()
            ->whereBarcode($barcode)
            ->whereNotIn('receiptunits.statusid', $status)->lockForUpdate()->first();
    }

    public static function searchForBarcodeTruck($barcodetruck)
    {
        return ReceiptUnit::controlAccessUser()
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->where('receiptunits.foliotruck', $barcodetruck)->lockForUpdate()->first();
    }

    public static function searchForId($id)
    {
        return ReceiptUnit::controlAccessUser()
            ->where('receiptunits.id', $id)
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->lockForUpdate()->first();
    }

    public static function searchForBoxNumber($boxnumber)
    {
        return ReceiptUnit::controlAccessUser()
            ->where('receiptunits.boxnumber', $boxnumber)
            ->whereNotIn('receiptunits.statusid', ['TR'])->lockForUpdate()->first();
    }
}
