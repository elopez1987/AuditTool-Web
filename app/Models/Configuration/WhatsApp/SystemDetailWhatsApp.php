<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class SystemDetailWhatsApp extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $table = 'systemdetailwhatsapps';
    protected $primaryKey = 'recordid';

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
