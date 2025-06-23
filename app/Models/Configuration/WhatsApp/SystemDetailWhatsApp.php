<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Database\Eloquent\Model;

class SystemDetailWhatsApp extends Model
{
    protected $table = "systemdetailwhatsapps";
    public $timestamps = false;
    protected $primaryKey = 'recordid';
    public $incrementing = true;

    protected $fillable = [
        'whatsappid',
        'centerid',
        'warehouseid',
        'namecontact',
        'phonenumber',
        'phonenumber01',
        'phonenumber02',
        'phonenumber03',
    ];

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return SystemDetailWhatsApp::whereIn('systemdetailwhatsapps.warehouseid', $warehouses)
            ->whereIn('systemdetailwhatsapps.centerid', $centers);
    }

}
