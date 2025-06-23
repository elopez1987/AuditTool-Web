<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;

class SendTelegram extends Model
{

    use Notifiable;


    protected $table = 'telegrams';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'notice',
        'noticedes',
        'noticelink',
        'telegramid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    static function sendNotificationForCreationOfLocation($location): SendTelegram
    {
        $notice = new SendTelegram([
            'notice' => 'NUEVA UBICACION',
            'noticedes' => 'C. Distribución: ' . $location->centerid . "\n" .
                'Nombre: ' . $location->name,
            'noticelink' => 'http://proyecto.sid/public/',
            'telegramid' => Config::get('services.telegram_yard')
        ]);
        $notice->save();
        return $notice;
    }


}
