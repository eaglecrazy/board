<?php


namespace App\Notifications;


use App\Entity\User\User;
use App\Notifications\Advert\ModerationPassedNotification;
use App\Services\Sms\SmsSender;

class SmsChannel
{
    private $sender;

    public function __construct(SmsSender $sender)
    {
        $this->sender = $sender;
    }

    public function send(User $notifiable, ModerationPassedNotification $notification): void
    {
        if(!$notifiable->isPhoneVerified()){
            return;
        }

        $message = $notification->toSms($notifiable);
        $this->sender->send($notifiable->phone, $message);
    }
}
