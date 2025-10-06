<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $workday
 * @property string|null $bodega
 * @property string|null $turno
 * @property string|null $estatus
 * @property string $embarque
 * @property string|null $cliente
 * @property string|null $destino
 * @property string|null $confirmation
 * @property string|null $linea
 * @property string|null $boxnumber
 * @property string|null $trucknumber
 * @property string|null $operatorname
 * @property int|null $pick
 * @property int|null $pallets
 * @property string|null $avance
 * @property string $fcitaconcliente
 * @property string|null $fcarga
 * @property string|null $fentrada
 * @property string|null $llegadaamesa
 * @property string|null $lanzamientoola
 * @property string|null $preparadoenpiso
 * @property string|null $porcargar
 * @property string|null $iniciacarga
 * @property string|null $fincarga
 * @property string|null $enviodocumentos
 * @property string|null $iniciofacturacion
 * @property string|null $finfacturacion
 * @property string|null $autorizasalida
 * @property string|null $maxsalida
 * @property string|null $salidadeunidad
 * @property string $estatusdearribo
 * @property string $estatusdedespacho
 * @property int|null $horasrestante
 * @property int|null $horastrayecto
 * @property int|null $horasenpatio
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereAutorizasalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereAvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereBodega($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereCliente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereDestino($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereEmbarque($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereEnviodocumentos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereEstatusdearribo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereEstatusdedespacho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereFcarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereFcitaconcliente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereFentrada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereFincarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereFinfacturacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereHorasenpatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereHorasrestante($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereHorastrayecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereIniciacarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereIniciofacturacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereLanzamientoola($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereLinea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereLlegadaamesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereMaxsalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent wherePallets($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent wherePick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent wherePorcargar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent wherePreparadoenpiso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereSalidadeunidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereTurno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewShipmentEvent whereWorkday($value)
 *
 * @mixin \Eloquent
 */
class ViewShipmentEvent extends Model
{
    protected $table = 'vw_monitorshipments';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return ViewShipmentEvent::whereIn('vw_monitorshipments.centerid', $centers);
    }
}
