<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $centerid
 * @property int $id
 * @property string $zona
 * @property int|null $E
 * @property int|null $D
 * @property-read SystemCenterDistribution|null $center
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone whereD($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone whereE($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayoutCapacityZone whereZona($value)
 *
 * @mixin \Eloquent
 */
class LayoutCapacityZone extends Model
{
    use Notifiable;

    protected $table = 'storagecapacityzone';

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return LayoutCapacityZone::whereIn('centerid', $centers);
    }
}
