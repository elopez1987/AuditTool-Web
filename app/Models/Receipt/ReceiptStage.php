<?php

namespace App\Models\Receipt;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageLocation;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptStage extends Model
{
//    public $timestamps = false;

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receiptstage';

    protected $fillable = [
        'id',
        'centerid',
        'warehouseid',
        'receiptid',
        'locationid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_at' => 'datetime:d/m/Y H:s',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptStage $model) {
                $model->createuser = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptStage::whereIn('receiptstage.warehouseid', $warehouses)
            ->whereIn('receiptstage.centerid', $centers);
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }


}
