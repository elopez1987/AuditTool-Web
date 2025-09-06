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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Storagechecklocationbridg extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'storagechecklocationbridg';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'workday',
        'orderid',
        'turnid',
        'locationid',
        'complemento',
        'totalizquierdo',
        'totalderecho',
        'totalfinal',
        'detalleizquierdo',
        'detallederecho',
        'name',
        'roleid',
        'namesupervisor',
        'keysupervisor',
        'value01',
        'value02',
        'value03',
        'team',
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
        return Storagechecklocationbridg::whereIn('storagechecklocationbridg.warehouseid', $warehouses)
            ->whereIn('storagechecklocationbridg.centerid', $centers);
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
