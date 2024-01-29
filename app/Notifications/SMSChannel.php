<?php

namespace App\Notifications;


use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);

        $receptor = $notifiable->routeNotificationFor('sms');

        logger()->info("sms send $receptor, $message");
    }
}
