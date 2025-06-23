<?php

namespace App\Models\Configuration\Mails;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageTaskMail extends Model
{
//    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageTaskMail $model) {
                $model->created_at = Carbon::now();
            });
        }
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

    static function createNewTaskMailReceipt($receiptunit)
    {
        $model = ReceiptAsn::controlAccessUser()->where('shipmentid', $receiptunit->shipmentid)
            ->whereNot('statusid', ['IG'])->first();
        if (!empty($model)) {
            self::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'modelid' => $model->id,
                'modeltype' => 'receiptasn',
                'mailid' => 'MAIL-RECYMS',
            ]);
        }
    }


}
