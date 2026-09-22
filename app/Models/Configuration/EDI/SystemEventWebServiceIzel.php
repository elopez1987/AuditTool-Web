<?php

namespace App\Models\Configuration\EDI;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $folionumber
 * @property string|null $boxnumber
 * @property string|null $servicename
 * @property string|null $processdate
 * @property string|null $code
 * @property string|null $type
 * @property string|null $message
 * @property array<array-key, mixed>|null $messagejson
 * @property array<array-key, mixed>|null $messagejsonresponse
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemWarehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel folioNumber($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessagejson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessagejsonresponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereProcessdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereServicename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class SystemEventWebServiceIzel extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'systemeventwebserviceizel';

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

    public static function controlAccessUser()
    {
        $username = Auth::user()->username;

        return SystemEventWebServiceIzel::query()
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
