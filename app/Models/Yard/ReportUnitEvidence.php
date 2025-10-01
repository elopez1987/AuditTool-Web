<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folionumber
 * @property string|null $shipmentid
 * @property string|null $boxnumber
 * @property string|null $econumber
 * @property string|null $carriername
 * @property string|null $tuckid
 * @property string $entrydate
 * @property string|null $usuarioenrampe
 * @property string|null $fechaenrampe
 * @property string|null $zonename
 * @property string|null $locationname
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property int|null $fotosentradas
 * @property string|null $usuarioentrada
 * @property int|null $fotosproceso
 * @property string|null $usuarioproceso
 * @property int|null $fotoscalidad
 * @property string|null $usuariocalidad
 * @property int|null $fotossalidas
 * @property string|null $usuariosalida
 * @property string|null $concepto
 * @property string|null $origen
 * @property string|null $status
 * @property string $enpatio
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereCarriername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereConcepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereEnpatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereEntrydate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFechaenrampe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFotoscalidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFotosentradas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFotosproceso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereFotossalidas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereLocationname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereTuckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereUsuariocalidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereUsuarioenrampe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereUsuarioentrada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereUsuarioproceso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereUsuariosalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportUnitEvidence whereZonename($value)
 * @mixin \Eloquent
 */
class ReportUnitEvidence extends Model
{
    protected $table = 'vw_storageunitevidence';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ReportUnitEvidence::whereIn('vw_storageunitevidence.centerid', $centers);
    }
}
