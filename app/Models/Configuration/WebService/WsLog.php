<?php

namespace App\Models\Configuration\WebService;

use Illuminate\Database\Eloquent\Model;

class WsLog extends Model
{
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


    static function createLog($model, $service, $request)
    {
        $log = WsLog::create([
            'model' => $model,
            'service' => $service,
            'request' => $request,
        ]);
        return $log;
    }

}
