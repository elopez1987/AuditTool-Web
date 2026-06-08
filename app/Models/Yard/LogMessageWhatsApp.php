<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $centerid
 * @property string|null $shipmentid
 * @property string|null $folionumber
 * @property string|null $message
 * @property string|null $chatid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string|null $value06
 * @property string|null $value07
 * @property string|null $value08
 * @property string|null $value09
 * @property string|null $value10
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp forNumber($number)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereChatid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue06($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue07($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue08($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue09($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogMessageWhatsApp whereValue10($value)
 *
 * @mixin \Eloquent
 */
class LogMessageWhatsApp extends Model
{
    use LogsActivity;

    protected $table = 'logsmessagewhatsapp';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'shipmentid',
        'folionumber',
        'message',
        'chatid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'value06',
        'value07',
        'value08',
        'value09',
        'value10',
    ];

    protected $casts = [
        //        'created_at' => 'datetime:d/m/Y H:s',
    ];

    protected $dates = ['value01'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('logsmessagewhatsapp.shipmentid', $shipmentid);
        }
    }

    public function scopeForNumber($query, $number)
    {
        if (trim($number) !== '') {
            $query->where('chatid', $number);
        }
    }

    public static function createLog($model, $contact, $resp, $whatsappid, $message): void
    {
        LogMessageWhatsApp::create([
            'centerid' => $model->centerid,
            'shipmentid' => $model->shipmentid,
            'folionumber' => $model->folionumber,
            'chatid' => $contact->phonenumber,
            'message' => $message,
            'value01' => $whatsappid,
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
        ]);
    }
}
