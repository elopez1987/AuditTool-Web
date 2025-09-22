<?php

namespace App\Models\Configuration\Folios;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemFolio extends Model
{
    use LogsActivity;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'systemfolio';

    protected $fillable = [
        'centerid',
        'folionumber',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'FOL',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return SystemFolio::whereIn('systemfolio.centerid', $centers);
    }

    public function scopeDescription($query, $description)
    {
        if (trim($description) !== '') {
            $query->where('systemstatus.name', 'like', "%{$description}%");
        }
    }
}
