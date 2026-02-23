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

/**
 * @property int $id
 * @property string|null $centerid
 * @property string $warehouseid
 * @property \Illuminate\Support\Carbon|null $workday
 * @property int $orderid
 * @property string|null $team
 * @property int $turnid
 * @property int|null $totalIzquierdo
 * @property int|null $sobranteizq
 * @property int|null $ncizq
 * @property int $locationid
 * @property array<array-key, mixed>|null $detalleizquierdo
 * @property int|null $sobranteder
 * @property int|null $ncder
 * @property int|null $totalderecho
 * @property array<array-key, mixed>|null $detallederecho
 * @property int|null $totalfinal
 * @property string|null $name
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution|null $center
 * @property-read StorageLocation|null $location
 * @property-read OrderDeliverie $order
 * @property-read StorageTurn|null $turn
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereDetallederecho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereDetalleizquierdo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereNcder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereNcizq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereSobranteder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereSobranteizq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereTotalIzquierdo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereTotalderecho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereTotalfinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storagechecklocationbridg whereWorkday($value)
 *
 * @mixin \Eloquent
 */
class Storagechecklocationbridg extends Model
{
    use LogsActivity;

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
        'model_type',
    ];

    protected $casts = [
        'workday' => 'date',
        'detalleizquierdo' => 'array',
        'detallederecho' => 'array',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function verification(): BelongsTo
    {
        return $this->belongsTo(Storageverificationpending::class, 'id', 'countoperid');
    }
}
