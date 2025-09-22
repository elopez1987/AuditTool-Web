<?php

namespace App\Models\Receipt;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

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
