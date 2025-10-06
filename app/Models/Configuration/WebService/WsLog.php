<?php

namespace App\Models\Configuration\WebService;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $model
 * @property string|null $service
 * @property array<array-key, mixed>|null $request
 * @property array<array-key, mixed>|null $response
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WsLog whereService($value)
 *
 * @mixin \Eloquent
 */
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
