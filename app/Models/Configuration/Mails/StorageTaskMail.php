<?php

namespace App\Models\Configuration\Mails;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $modeltype
 * @property string $modelid
 * @property string $mailid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemSchema $schema
 * @property-read SystemStatus|null $status
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereMailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereModeltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskMail whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageTaskMail extends Model
{
    use LogsActivity;

    protected $table = 'storagetaskmails';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'modeltype',
        'modelid',
        'mailid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'schemaid',
        'statusid',
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

        return StorageTaskMail::whereIn('storagetaskmails.warehouseid', $warehouses)
            ->whereIn('storagetaskmails.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function schema(): BelongsTo
    {
        return $this->belongsTo(SystemSchema::class, 'schemaid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function createNewTaskMailReceipt($receiptunit)
    {
        $model = ReceiptAsn::controlAccessUser()->where('shipmentid', $receiptunit->shipmentid)
            ->whereNot('statusid', ['IG'])->first();
        if (! empty($model)) {
            self::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'modelid' => $model->id,
                'modeltype' => 'receiptasn',
                'mailid' => 'MAIL-RECYMS',
            ]);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageTaskMail $model) {
                $model->created_at = Carbon::now();
            });
        }
    }
}
