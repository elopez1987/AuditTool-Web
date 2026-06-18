<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tourid
 * @property string $centerid
 * @property int|null $tagid
 * @property int|null $sequence
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string $schematype
 * @property string|null $statustype
 * @property string|null $comment
 * @property string|null $evidencefile
 * @property int|null $userid
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read SystemStatus|null $categorytype
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read StorageTags|null $tag
 * @property-read StorageSafetyTours $tour
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereEvidencefile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereSchematype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereStatustype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereTagid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereTourid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageSafetyTourDetail whereUserid($value)
 *
 * @mixin \Eloquent
 */
class StorageSafetyTourDetail extends Model
{
    protected $table = 'storagesafetytourdetail';

    protected $fillable = [
        'centerid',
        'tourid',
        'tagid',
        'sequence',
        'latitude',
        'longitude',
        'schematype',
        'statustype',
        'comment',
        'evidencefile',
        'userid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'RSE-01',
        'schemaid' => 'RSE',
        'schematype' => 'CTR',  // Esquema de categorias de registro
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageSafetyTourDetail::whereIn('storagesafetytourdetail.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function tag()
    {
        return $this->belongsTo(StorageTags::class, 'tagid', 'id')->withTrashed();
    }

    public function tour()
    {
        return $this->belongsTo(StorageSafetyTours::class, 'tourid', 'id');
    }

    public function categorytype()
    {
        return $this->belongsTo(SystemStatus::class, 'statustype', 'statusid');
    }
}
