<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property SystemWarehouse|null $warehouse
 * @property int $id
 * @property string|null $barcode
 * @property string|null $shipmentid
 * @property string|null $linea
 * @property string|null $carriername
 * @property string|null $boxnumber
 * @property string|null $econumber
 * @property string|null $trucknumber
 * @property string|null $tipo
 * @property string|null $operatorname
 * @property string $registro
 * @property string|null $llegadamesa
 * @property string|null $llaves
 * @property string|null $enrampe
 * @property string|null $inicioproceso
 * @property string|null $finproceso
 * @property string|null $autorizasalida
 * @property string|null $salida
 * @property int|null $tentrada
 * @property int|null $tiniciocarga
 * @property int|null $tcarga
 * @property int|null $tsalida
 * @property string $statusid
 * @property SystemStatus|null $status
 * @property string|null $ticket
 * @property string|null $weightin
 * @property string|null $weightout
 * @property-read SystemCenterDistribution|null $center
 * @property-read mixed $operator_name
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock folio($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock reason($razonstatusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock shipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereAutorizasalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereCarriername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereEnrampe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereFinproceso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereInicioproceso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereLinea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereLlaves($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereLlegadamesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereRegistro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTcarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTentrada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTiniciocarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereTsalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereWeightin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageBoxStock whereWeightout($value)
 *
 * @mixin \Eloquent
 */
class StorageBoxStock extends Model
{
    protected $table = 'vw_storageboxstock';

    protected $casts = [
        'entrydate' => 'datetime:d/m/Y H:i',
        'deliverdate' => 'datetime:d/m/Y H:i',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return StorageBoxStock::whereIn('vw_storageboxstock.centerid', $centers)
            ->whereIn('vw_storageboxstock.warehouseid', $warehouses);
    }

    public function getOperatorNameAttribute($value)
    {
        return ucwords($value);
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

    public function unit()
    {
        return $this->belongsTo(ReceiptUnit::class, 'id', 'id');
    }

    //	Filtros de busqueda
    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != '') {
            $query->where('vw_storageboxstock.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != '') {
            $query->where('vw_storageboxstock.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('vw_storageboxstock.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('vw_storageboxstock.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeReason($query, $razonstatusid)
    {
        if (trim($razonstatusid) != '') {
            $query->where('receiptunits.razonstatusid', $razonstatusid);
        }
    }
}
