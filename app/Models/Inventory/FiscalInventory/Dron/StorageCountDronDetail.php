<?php

namespace App\Models\Inventory\FiscalInventory\Dron;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageCountDronDetail extends Model
{
    public $timestamps = false;

    protected $table = 'storagecountdronheaddetail';

    protected $fillable = [
        'centerid', 'inventoryid', 'countid', 'footprint', 'wd', 'lpn', 'ubicacion',
        'ultimo_movimiento', 'estatus', 'idh', 'cantidad_pz', 'uom', 'cajas',
        'lote', 'fecha_manufactura', 'fecha_expiracion', 'fecha_de_alta', 'ultimo_usuario',
        'familia', 'descripcion', 'area', 'match_wd', 'real', 'incidencia', 'nota', 'comentario',
        'auxiliary01', 'auxiliary02', 'auxiliary03',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCountDronDetail::whereIn('storagecountdronheaddetail.centerid', $centers);
    }

    public function scopeIDH($query, $idh)
    {
        if (trim($idh) != '') {
            $query->where('idh', $idh);
        }
    }

    public function scopeLocation($query, $location)
    {
        if (trim($location) != '') {
            $query->where('ubicacion', $location);
        }
    }

    public function scopeArea($query, $area)
    {
        if (trim($area) != '') {
            $query->where('area', $area);
        }
    }

    public function scopeFamily($query, $family)
    {
        if (trim($family) != '') {
            $query->where('storagecountdronheaddetail.familia', $family);
        }
    }

}
