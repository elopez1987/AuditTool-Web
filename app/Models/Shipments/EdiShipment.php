<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $shipmentid
 * @property string|null $client
 * @property string|null $type
 * @property string|null $message
 * @property int|null $quantity
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Shipments\OrderDeliverie|null $shipment
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiShipment whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class EdiShipment extends Model
{
    use LogsActivity;

    protected $table = 'edishipments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'type',
        'reference_number',
        'external_system',
        'retry_count',
        'transaction_id',
        'document_type',
        'event_type',
        'model_type',
        'request_payload',
        'response_payload',
        'error_message',
        'error_code',
        'processed_at',
        'last_retry_at',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return EdiShipment::whereIn('edishipments.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function shipment()
    {
        return $this->belongsTo(OrderDeliverie::class, 'shipmentid', 'shipmentid');
    }
}
