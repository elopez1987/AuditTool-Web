<?php

namespace App\Models\Security\Users;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $username
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $defaultlevel
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereDefaultlevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAccessWarehouse whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class SecurityAccessWarehouse extends Model
{
    use LogsActivity;

    protected $table = 'securityaccesswarehouses';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'username',
        'defaultlevel',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function Centers()
    {
        return (new SecurityAccessWarehouse)
            ->where('username', Auth::user()->username)
            ->select('centerid')
            ->get()
            ->toArray();
    }

    public static function Warehouses()
    {
        return (new SecurityAccessWarehouse)
            ->where('username', Auth::user()->username)
            ->select('warehouseid')
            ->get()
            ->toArray();
    }

    public static function getCenterForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }
}
