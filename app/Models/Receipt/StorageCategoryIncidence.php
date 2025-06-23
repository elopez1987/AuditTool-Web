<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageCategoryIncidence extends Model
{

    protected $table = 'storagecategoryincidences';
    protected $primaryKey = 'categoryincid';
    public $timestamps = false;


    protected $fillable = [
        'centerid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E'
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCategoryIncidence::whereIn('storagecategoryincidences.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }


    public function scopeForDescription($query, $description)
    {
        if (trim($description) != "") {
            $query->where('storagecategoryincidences.description', 'like', "%$description%");
        }
    }

}
