<?php

namespace App\Models\Configuration\CenterDistribution;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'statusid',
        'configurationflag',
        'notificationsflags',
        'mobileflags',
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
        'mobileflags' => 'array',
    ];

    public static function controlAccessUser(): Builder
    {

        $username = Auth::user()->username;

        return SystemCenterDistribution::query()
            ->whereIn(
                'systemcenterdistributions.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            );
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
