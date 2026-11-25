<?php

declare(strict_types=1);

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Yard\StorageOperator;
use App\Observers\Receipt\ReceiptAsnObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $uuid_asn
 * @property string $centerid
 * @property string $warehouseid
 * @property string $statusid
 */
#[ObservedBy([ReceiptAsnObserver::class])]
class ReceiptAsn extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receiptasn';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'uuid_asn',
        'centerid', 'warehouseid', 'providerid', 'shipmentid', 'billoflading',
        'addasndate', 'cancellationdate', 'goodissuedate', 'created_by', 'closed_by',
        'statusid', 'workday', 'operatorid',
    ];

    protected $attributes = [
        'schemaid' => 'SRE',
    ];

    /** @return Builder<static> */
    public static function controlAccessUser(): Builder
    {
        $username = Auth::user()->username;

        return ReceiptAsn::query()->whereExists(function (QueryBuilder $query) use ($username): void {
            $query->selectRaw('1')->from('securityaccesswarehouses')
                ->where('username', $username)
                ->whereColumn('securityaccesswarehouses.centerid', 'receiptasn.centerid')
                ->whereColumn('securityaccesswarehouses.warehouseid', 'receiptasn.warehouseid');
        });
    }

    //    ------------------------------ Relaciones ------------------------------------------
    public function center(): BelongsTo
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

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(StorageOperator::class, 'operatorid', 'id');
    }

    public function stockplan(): HasMany
    {
        return $this->hasMany(ReceiptStock::class, 'receiptid', 'id');
    }

    public function stockcredits(): HasMany
    {
        return $this->hasMany(ReceiptStockCredit::class, 'receiptasn_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(ReceiptVerification::class, 'receiptasn_id');
    }

    public function timereceipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsnTime::class, 'id', 'receiptid');
    }

    public function receiptdata(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsnData::class, 'id', 'receiptid');
    }

    //  -----------------------------------------  Funciones auxiliares -------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptAsn $model) {
                $model->created_by = Auth::id();
                $model->uuid_asn = Uuid::uuid7()->toString();
            });
        }
    }
}
