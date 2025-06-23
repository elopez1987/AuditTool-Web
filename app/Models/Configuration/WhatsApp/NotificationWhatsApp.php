<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationWhatsApp extends Model
{
    protected $table = 'systemwhatsapps';
    protected $primaryKey = 'whatsappid';
    public $timestamps = false;
    public $incrementing = false;


    protected $fillable = [
        'whatsappid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'configurationflag',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function detailwhatsapp()
    {
        return $this->hasMany(SystemDetailWhatsApp::class, 'whatsappid', 'whatsappid');
    }


    public function scopeWhatsAppId($query, $whatsappid)
    {
        if (trim($whatsappid) != '') {
            $query->where('systemwhatsapps.whatsappid', 'like', "%$whatsappid%");
        }
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('systemwhatsapps.description', 'like', "%$name%");
        }
    }





}
