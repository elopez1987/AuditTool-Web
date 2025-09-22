<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScheduleInverseLog extends Model
{
    use LogsActivity;

    protected $table = 'scheduleinverselog';

    protected $fillable = [
        'orderid', 'username', 'description',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AG',
        'schemaid' => 'LIN',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function createNewLog($shipment, $description)
    {
        ScheduleInverseLog::create([
            'orderid' => $shipment->id,
            'username' => Auth::user()->username,
            'description' => $description,
        ]);
    }
}
