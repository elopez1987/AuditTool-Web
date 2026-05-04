<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Receipt\StorageReceiptPeriods\StorageReceiptPeriod;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int|null $periodid
 * @property string $workday
 * @property int|null $turnid
 * @property string|null $secuence
 * @property int|null $receiptid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageReceiptPeriod|null $period
 * @property-read \App\Models\Receipt\ReceiptAsn|null $receipt
 * @property-read SystemStatus|null $status
 * @property-read SystemStatus|null $statussecuence
 * @property-read StorageTurn|null $turn
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt shipment($shipment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt wherePeriodid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereSecuence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt whereWorkday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSchedulleReceipt workday($workday)
 *
 * @mixin \Eloquent
 */
class StorageSchedulleReceipt extends Model
{
    use LogsActivity;

    //	$value01 = Usuario que bloquea el registro para apartarlo

    protected $table = 'storageschedullereceipt';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'periodid',
        'workday',
        'turnid',
        'secuence',
        'receiptid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'schemaid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'DPR-01',
        'schemaid' => 'DPR',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageSchedulleReceipt::whereIn('storageschedullereceipt.warehouseid', $warehouses)
            ->whereIn('storageschedullereceipt.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function turn(): BelongsTo
    {
        return $this->belongsTo(StorageTurn::class, 'turnid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function statussecuence(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'secuence', 'statusid');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptid', 'id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(StorageReceiptPeriod::class, 'periodid');
    }

    //    Filtros
    public function scopeWorkday($query, $workday)
    {
        if (trim($workday) != '') {
            return $query->where('storageschedullereceipt.workday', '=', $workday);
        }
    }

    public function scopeShipment($query, $shipment)
    {
        if (trim($shipment) != '') {
            $query->where('storageschedullereceipt.shipmentid', '=', $shipment);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageSchedulleReceipt $model) {});
        }
    }
}
