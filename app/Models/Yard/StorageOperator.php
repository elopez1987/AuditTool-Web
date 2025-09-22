<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageOperator extends Model
{
    use LogsActivity;
    protected $table = 'storageoperators';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'barcode',
        'carrierid',
        'drivernumber',
        'phone',
        'name',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
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

    protected $casts = [
        'barcode' => 'string',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

//    Relations with models
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

//    Filters Scope to model
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storageoperators.name', 'like', "%{$name}%");
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('storageoperators.barcode', '=', $barcode);
        }
    }

    public function scopeForCarrierOperator($q, $carrier)
    {
        if (! empty($carrier)) {
            $q->whereIn('storageoperators.carrierid', $carrier);
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageOperator::whereIn('storageoperators.centerid', $centers);
    }
}
