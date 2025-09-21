<?php

namespace App\Models\Configuration\Labels;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryLabelDetail extends Model
{
    use LogsActivity;
    public $timestamps = false;

    protected $table = 'inventorylabeldetails';
    protected $keyType = 'integer';

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function getDetailForLabel($label)
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryLabelDetail::join('inventorylabels', 'inventorylabeldetails.labelid', '=', 'inventorylabels.labelid')
            ->whereIn('inventorylabels.centerid', $centers)
            ->where('inventorylabels.auxiliary01', '=', $label)
            ->select('inventorylabeldetails.description')->get();
    }
}
