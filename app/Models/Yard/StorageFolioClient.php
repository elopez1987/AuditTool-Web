<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $folionumber
 * @property int|null $foliotruck
 * @property string $folioclient
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereFolioclient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereFoliotruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageFolioClient whereId($value)
 *
 * @mixin \Eloquent
 */
class StorageFolioClient extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagefolioclients';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'folionumber',
        'folioclient',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageFolioClient::whereIn('storagefolioclients.centerid', $centers);
    }
}
