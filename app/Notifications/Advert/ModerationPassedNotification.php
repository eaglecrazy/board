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
    private $url;


    public function __construct(Advert $advert, $url)
    {
        $this->advert = $advert;
        $this->url = $url;
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
            ->action('Посмотреть объявление', $this->url)
            ->line('Спасибо за использование нашего сайта!');
    }

    public function toSms($notifiable): string
    {
        return 'Объявление ' . $this->advert->title . ' прошло модерацию и теперь видно всем пользователям.';
    }
}
