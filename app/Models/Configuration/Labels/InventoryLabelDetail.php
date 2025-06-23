<?php

namespace App\Models\Configuration\Labels;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class InventoryLabelDetail extends Model
{

    protected $table = 'inventorylabeldetails';
    protected $keyType = 'integer';
    public $timestamps = false;


    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'labelid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];


    static function getDetailForLabel($label)
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryLabelDetail::join('inventorylabels', 'inventorylabeldetails.labelid', '=', 'inventorylabels.labelid')
            ->whereIn('inventorylabels.centerid', $centers)
            ->where('inventorylabels.auxiliary01', '=', $label)
            ->select('inventorylabeldetails.description')->get();
    }


}
