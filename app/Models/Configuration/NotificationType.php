<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationType extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'notification_types';

    protected $fillable = [
        'key_notification',
        'label',
        'description',
        'schemaid',
        'statusid',
    ];
}
