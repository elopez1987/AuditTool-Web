<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageCheckListDetail extends Model
{
    use Notifiable;
    use LogsActivity;

    protected $table = 'storagechecklistdetails';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'checklistid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCheckListDetail::whereIn('storagechecklistdetails.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function checklist()
    {
        return $this->belongsTo(StorageCheckList::class, 'checklistid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }
}
