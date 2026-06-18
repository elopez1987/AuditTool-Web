<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
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
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereBaseuri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereServicetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EdiToken whereTokentype($value)
 *
 * @mixin \Eloquent
 */
class EdiToken extends Model
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
        'servicetype',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
