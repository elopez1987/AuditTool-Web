<?php

namespace App\Models\Shipments\ShipmentTypes;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentType extends Model
{
    protected $table = 'shipmenttypes';


    protected $fillable = [
        'centerid',
        'name',
        'configurationflag',
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

//***************************** Relaciones entre modelos **********************************
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentType::whereIn('centerid', $centers);
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('name', 'like', "%$name%");
        }
    }

}
