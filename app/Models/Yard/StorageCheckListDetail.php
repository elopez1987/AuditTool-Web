<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageCheckListDetail extends Model
{
    use LogsActivity;
    use Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagechecklistdetails';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'checklistid',
        'description',
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(StorageCheckList::class, 'checklistid');
    }

    public function scopeName($query, $name): void
    {
        if (trim($name) != '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }
}
