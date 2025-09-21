<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
