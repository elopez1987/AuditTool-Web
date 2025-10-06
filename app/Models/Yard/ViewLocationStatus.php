<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property int|null $E
 * @property int|null $D
 * @property-read SystemCenterDistribution|null $center
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus whereD($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ViewLocationStatus whereE($value)
 *
 * @mixin \Eloquent
 */
class ViewLocationStatus extends Model
{
    protected $table = 'viewlocationstatus';

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return ViewLocationStatus::whereIn('centerid', $centers);
    }
}
