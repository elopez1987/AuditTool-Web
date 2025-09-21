<?php

namespace App\Models\Security\Users;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
        return (new SecurityAccessWarehouse())
            ->where('username', Auth::user()->username)
            ->select('centerid')
            ->get()
            ->toArray();
    }

    public static function Warehouses()
    {
        return (new SecurityAccessWarehouse())
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
