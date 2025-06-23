<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramRegister extends Notification
{

    public function via()
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegramid)
            ->content("*" . $notifiable->notice . "*\n" . $notifiable->noticedes)
            ->button("Ir al Sistema", $notifiable->noticelink);
    }


}
