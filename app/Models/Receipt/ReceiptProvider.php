<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $providerid
 * @property string $name
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereProviderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptProvider whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptProvider extends Model
{
    use LogsActivity;

    protected $table = 'receiptproviders';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['centerid', 'warehouseid', 'providerid',
        'name', 'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04',
        'auxiliary05', 'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ReceiptProvider::whereIn('receiptproviders.warehouseid', $warehouses)
            ->whereIn('receiptproviders.centerid', $centers);
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

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('receiptproviders.name', 'like', "%{$name}%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != '') {
            $query->where('receiptproviders.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeProvider($query, $providers)
    {
        if (! empty($providers)) {
            $query->whereIn('receiptproviders.id', $providers);
        }
    }
}
