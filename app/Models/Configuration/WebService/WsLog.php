<?php

namespace App\Models\Configuration\WebService;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WsLog extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'ediwslogs';

    protected $fillable = [
        'model',
        'service',
        'response',
        'request',
    ];

    protected $casts = [
        'response' => 'json',
        'request' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function createLog($model, $service, $request)
    {
        return WsLog::create([
            'model' => $model,
            'service' => $service,
            'request' => $request,
        ]);
    }
}
