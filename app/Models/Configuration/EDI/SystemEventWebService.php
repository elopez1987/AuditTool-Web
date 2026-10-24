<?php

namespace App\Models\Configuration\EDI;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemEventWebService extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'systemeventwebservice';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folionumber',
        'boxnumber',
        'servicename',
        'processdate',
        'code',
        'message',
        'messagejson',
        'messagejsonresponse',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'type',
    ];

    protected $casts = [
        'messagejson' => 'json',
        'messagejsonresponse' => 'json',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public static function controlAccessUser(): Builder
    {
        $username = Auth::user()->username;

        return SystemEventWebService::query()
            ->whereIn(
                'systemeventwebserviceizel.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            )
            ->whereIn(
                'systemeventwebserviceizel.warehouseid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('warehouseid'),
            );
    }
}
