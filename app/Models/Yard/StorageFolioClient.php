<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageFolioClient extends Model
{
    protected $table = 'storagefolioclients';
    public $timestamps = false;
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

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageFolioClient::whereIn('storagefolioclients.centerid', $centers);
    }
}
