<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $whatsappid
 * @property string $centerid
 * @property string $warehouseid
 * @property string $namecontact
 * @property string $phonenumber
 * @property string|null $phonenumber01
 * @property string|null $phonenumber02
 * @property string|null $phonenumber03
 * @property-read SystemCenterDistribution $center
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp whereNamecontact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp wherePhonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp wherePhonenumber01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp wherePhonenumber02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp wherePhonenumber03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemDetailWhatsApp whereWhatsappid($value)
 *
 * @mixin \Eloquent
 */
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
