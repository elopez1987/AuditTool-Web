<?php

namespace App\Models\Receipt;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property int $categoryincid
 * @property int $categoryincdetailid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read StorageCategoryIncidence $category
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereCategoryincdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereCategoryincid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCategoryIncidenceDetail whereStatusid($value)
 *
 * @mixin \Eloquent
 */
class StorageCategoryIncidenceDetail extends Model
{
    public $timestamps = false;

    protected $table = 'storagecategoryincidencedetails';

    protected $primaryKey = 'categoryincdetailid';

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    protected $fillable = [
        'centerid',
        'categoryincid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageCategoryIncidenceDetail::whereIn('storagecategoryincidencedetails.centerid', $centers);
    }

    public function category()
    {
        return $this->belongsTo(StorageCategoryIncidence::class, 'categoryincid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }
}
