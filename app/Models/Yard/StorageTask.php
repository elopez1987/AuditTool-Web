<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageTask extends Model
{

    use LogsActivity;
    protected $table = 'storagetasks';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'receiptunitid',
        'razonstatusid',
        'operatoryard',
        'assignedto',
        'zoneid',
        'locationid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'PD',
        'schemaid' => 'SKS',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageTask::whereIn('storagetasks.warehouseid', $warehouses)
            ->whereIn('storagetasks.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operatoryard', 'id');
    }

    public function receiptunit()
    {
        return $this->belongsTo(ReceiptUnit::class, 'receiptunitid', 'id');
    }

    public function razon()
    {
        return $this->belongsTo(StorageReason::class, 'razonstatusid', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(StorageZone::class, 'zoneid', 'id');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public static function searchTaskForUnit($folionumber)
    {
        return StorageTask::controlAccessUser()->whereReceiptunitid($folionumber)
            ->whereStatusid('PD')
            ->first();
    }

    public function scopeForStatusTask($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('storagetasks.statusid', $statusid);
        } else {
            $query->where('storagetasks.statusid', 'PD');
        }
    }
}
