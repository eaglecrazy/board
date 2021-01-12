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
            ->subject('Moderation is passed')
            ->greeting('Hello!')
            ->line('Your advert successfully passed a moderation.')
            ->action('View Advert', route('adverts.shshow', $this->advert))
            ->line('Thank you for using our application!');
    }

    public function toSms($notifiable): string
    {
        return 'Your advert successfully passed a moderation.';
    }
}
