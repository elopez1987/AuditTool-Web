<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StorageShipmentFolios extends Model
{

    protected $table = 'storageshipmentfolios';

    protected $fillable = [
        'centerid', 'warehouseid', 'name', 'turnid', 'team', 'workday',
        'folioid', 'statusid', 'created_at',
        'updated_at', 'closeuserid', 'namesupervisor', 'keysupervisor', 'schemakeyid', 'statuskeyid'
    ];


    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
        'statuskeyid' => 'FIR-01',
        'schemakeyid' => 'FIR',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function ($model) {
                $model->userid = Auth::id();
                $model->name = Auth::user()->name;
                $model->workday = Carbon::now()->format('Y-m-d');
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageShipmentFolios::whereIn('storageshipmentfolios.warehouseid', $warehouses)
            ->whereIn('storageshipmentfolios.centerid', $centers);
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function statuskey()
    {
        return $this->belongsTo(SystemStatus::class, 'statuskeyid', 'statusid');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

}
