<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $whatsappid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $configurationflag
 * @property string $schemaid
 * @property string $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Configuration\WhatsApp\SystemDetailWhatsApp> $detailwhatsapp
 * @property-read int|null $detailwhatsapp_count
 * @property-read SystemStatus|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whatsAppId($whatsappid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationWhatsApp whereWhatsappid($value)
 *
 * @mixin \Eloquent
 */
class NotificationWhatsApp extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'systemwhatsapps';

    protected $primaryKey = 'whatsappid';

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
        'statusid' => 'E',
    ];

    protected $casts = [
        'configurationflag' => 'array',
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
        if (trim($whatsappid) !== '') {
            $query->where('systemwhatsapps.whatsappid', 'like', "%{$whatsappid}%");
        }
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('systemwhatsapps.description', 'like', "%{$name}%");
        }
    }
}
