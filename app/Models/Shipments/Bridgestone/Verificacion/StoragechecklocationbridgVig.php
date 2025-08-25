<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\StorageLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoragechecklocationbridgVig extends Model
{

    public $timestamps = false;

    protected $table = 'storagechecklocationbridgvig';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'workday',
        'orderid',
        'turnid',
        'locationid',
        'totalizquierdo',
        'totalderecho',
        'totalfinal',
        'detalleizquierdo',
        'detallederecho',
        'name',
        'value01',
        'value02',
        'value03',
        'ncizq',
        'sobranteizq',
        'sobranteder',
        'ncder',
    ];

    protected $casts = [
        'workday' => 'date',
        'detalleizquierdo' => 'array',
        'detallederecho' => 'array',

    ];


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StoragechecklocationbridgVig::whereIn('storagechecklocationbridgvig.warehouseid', $warehouses)
            ->whereIn('storagechecklocationbridgvig.centerid', $centers);
    }


    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid','id');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid','id');
    }



}
