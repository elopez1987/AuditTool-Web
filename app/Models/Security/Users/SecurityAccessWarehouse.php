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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'securityaccesswarehouses';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'username',
        'defaultlevel'
    ];

    static function Centers()
    {
        return (new SecurityAccessWarehouse())
            ->where('username', Auth::user()->username)
            ->select('centerid')
            ->get()
            ->toArray();
    }

    static function Warehouses()
    {
        return (new SecurityAccessWarehouse())
            ->where('username', Auth::user()->username)
            ->select('warehouseid')
            ->get()
            ->toArray();
    }

    static function getCenterForUser()
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
