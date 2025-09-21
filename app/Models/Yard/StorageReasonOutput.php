<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageReasonOutput extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected $table = 'storagereasonoutput';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'description',
        'type',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageReasonOutput::whereIn('centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid')
            ->select(['centerid', 'name']);
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid')
            ->select(['statusid', 'name']);
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('description', 'like', "%{$name}%");
        }
    }

    public static function getReasonOutPutsForType($type)
    {
        return StorageReasonOutput::controlAccessUser()
            ->where('type', $type)
            ->pluck('description', 'id')->toArray();
    }
}
