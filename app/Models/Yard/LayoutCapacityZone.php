<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

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
