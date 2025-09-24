<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string|null $warehouseid
 * @property string $modelid
 * @property string $modeltype
 * @property string $whatsappid
 * @property string|null $phone
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $created_at
 * @property string $schemaid
 * @property string|null $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read SystemWarehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereModeltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageTaskWhatsapp whereWhatsappid($value)
 * @mixin \Eloquent
 */
class StorageTaskWhatsapp extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'storagetaskwhatsapp';

    protected $fillable = [
        'centerid', 'warehouseid', 'modelid',
        'modeltype', 'whatsappid', 'phone ', 'value01',
        'value02', 'value03', 'value04', 'value05', 'statusid',
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

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageTaskWhatsapp::whereIn('storagetaskwhatsapp.warehouseid', $warehouses)
            ->whereIn('storagetaskwhatsapp.centerid', $centers);
    }

    public static function createNewTask($model, $key_id, $whatsapptype, $type)
    {
        StorageTaskWhatsapp::create([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'modelid' => $key_id,
            'modeltype' => $model->getTable(),
            'whatsappid' => $whatsapptype,
            'value01' => $type,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageTaskWhatsapp $model) {
                $model->created_at = Carbon::now();
            });
        }
    }
}
