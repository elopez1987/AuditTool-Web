<?php

namespace App\Models\Configuration\CenterDistribution;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method whereStatusid(string $string)
 * @method static whereIn(string $string, $centers)
 *
 * @property string $centerid
 * @property string $name
 * @property string $description
 * @property array<array-key, mixed>|null $configurationflag
 * @property int|null $capacityyard
 * @property string|null $url
 * @property string|null $userapi
 * @property string|null $passwordapi
 * @property string|null $code
 * @property string|null $formatinvoice
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property array<array-key, mixed>|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution forName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereCapacityyard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereFormatinvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution wherePasswordapi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemCenterDistribution whereUserapi($value)
 *
 * @mixin \Eloquent
 */
class SystemCenterDistribution extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected static $logAttributes = ['*'];

    protected $table = 'systemcenterdistributions';

    protected $primaryKey = 'centerid';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'description',
        'name',
        'code',
        'capacityyard',
        'url',
        'userapi',
        'passwordapi',
        'code',
        'formatinvoice',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
        'notificationsflags',
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

    protected $casts = [
        'configurationflag' => 'array',
        'notificationsflags' => 'array',
        'auxiliary05' => 'array',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return SystemCenterDistribution::whereIn('systemcenterdistributions.centerid', $centers)
            ->where('statusid', 'E');
    }

    public function scopeForName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('systemcenterdistributions.name', 'like', "%{$name}%");
        }
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
