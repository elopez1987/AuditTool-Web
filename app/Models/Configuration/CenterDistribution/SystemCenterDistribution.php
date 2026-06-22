<?php

namespace App\Models\Configuration\CenterDistribution;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

        return SystemCenterDistribution::whereIn('systemcenterdistributions.centerid', $centers);
    }

    public function scopeForName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('systemcenterdistributions.name', 'like', "%{$name}%");
        }
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
