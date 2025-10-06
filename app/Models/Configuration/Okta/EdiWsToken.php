<?php

namespace App\Models\Configuration\Okta;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $baseuri
 * @property string $client_id
 * @property string $client_secret
 * @property string|null $audience
 * @property int|null $timeout
 * @property string|null $tokentype
 * @property string|null $servicetype
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereBaseuri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereServicetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiWsToken whereTokentype($value)
 *
 * @mixin \Eloquent
 */
class EdiWsToken extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'ediwstokens';

    protected $fillable = [
        'baseuri',
        'client_id',
        'client_secret',
        'audience',
        'timeout',
        'tokentype',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
