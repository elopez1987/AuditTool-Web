<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
        'created_at' => 'datetime:d/m/Y H:s',
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
