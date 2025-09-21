<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
