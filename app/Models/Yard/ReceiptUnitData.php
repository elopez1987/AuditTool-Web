<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folionumber
 * @property string|null $typeoflicense
 * @property string|null $validity
 * @property string|null $brand
 * @property string|null $expedition
 * @property string|null $holder
 * @property string|null $fuellevel
 * @property string|null $mirrorright
 * @property string|null $mirrorleft
 * @property string|null $tire
 * @property string|null $floorwear
 * @property string|null $lightstruck
 * @property string|null $texttruck
 * @property string|null $skulltruck
 * @property string|null $skulltext
 * @property string|null $semitrailerbrand
 * @property string|null $trucknumsemi
 * @property string|null $correctm
 * @property string|null $checklist
 * @property string|null $observations
 * @property string|null $tires
 * @property string|null $tiresterxt
 * @property string|null $lights
 * @property string|null $lightstext
 * @property string|null $skullst
 * @property string|null $skulltexts
 * @property string|null $staples
 * @property string|null $patches
 * @property string|null $uneven
 * @property string|null $approved
 * @property string|null $observationsinspec
 * @property string|null $customername
 * @property string|null $providername
 * @property string|null $tolerance
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereChecklist($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereCorrectm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereCustomername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereExpedition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereFloorwear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereFuellevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereHolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereLights($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereLightstext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereLightstruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereMirrorleft($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereMirrorright($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereObservations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereObservationsinspec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData wherePatches($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereProvidername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereSemitrailerbrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereSkullst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereSkulltext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereSkulltexts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereSkulltruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereStaples($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTexttruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTires($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTiresterxt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTolerance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTrucknumsemi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereTypeoflicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereUneven($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereValidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitData whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ReceiptUnitData extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'receiptunitdata';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folionumber',
        'typeoflicense',
        'validity',
        'brand',
        'expedition',
        'holder',
        'fuellevel',
        'mirrorright',
        'mirrorleft',
        'tire',
        'floorwear',
        'lightstruck',
        'texttruck',
        'skulltruck',
        'skulltext',
        'semitrailerbrand',
        'trucknumsemi',
        'correctm',
        'checklist',
        'observations',
        'tires',
        'tiresterxt',
        'lights',
        'lightstext',
        'skullst',
        'skulltexts',
        'staples',
        'patches',
        'uneven',
        'approved',
        'observationsinspec',
        'customername',
        'providername',
        'tolerance',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnitData::whereIn('receiptunitdata.warehouseid', $warehouses)
            ->whereIn('receiptunitdata.centerid', $centers);
    }
}
