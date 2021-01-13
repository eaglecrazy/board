<?php

namespace App\Notifications\Advert;

use App\Entity\Adverts\Advert\Advert;
use App\Notifications\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ModerationPassedNotification extends Notification
{
    use Queueable, SerializesModels;

    private $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    public function via($notifiable): array
    {
        return ['mail', SmsChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Объявление ' . $this->advert->title . ' прошло модерацию.')
            ->greeting('Привет!')
            ->line('Объявление ' . $this->advert->title . ' прошло модерацию и теперь видно всем пользователям.')
//            ->action('View Advert', route('adverts.show', $this->advert))
            ->action('Посмотреть объявление', 'http://board.xyz/adverts/show/' . $this->advert->id)
            ->line('Спасибо за использование нашего сайта!');
    }

    public function toSms($notifiable): string
    {
        return 'Your advert successfully passed a moderation.';
    }
}
