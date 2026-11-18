<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
