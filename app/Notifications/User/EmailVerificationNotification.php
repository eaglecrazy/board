<?php

namespace App\Notifications\User;

use App\Entity\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class EmailVerificationNotification extends Notification
{
    use Queueable, SerializesModels;

    private $user;
    private $url;

    public function __construct(User $user, string $url)
    {
        $this->user = $user;
        $this->url = $url;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Регистрация пройдена!')
            ->greeting('Здравствуйте, ' . $this->user->name)
            ->greeting('Здравствуйте!')
            ->line('Регистрация на Фотобарахолке №1 пройдена. Для подтверждения почты нажимте на кнопку ниже.')
            ->action('Подтвердить почту', $this->url)
            ->line('Спасибо за использование нашего сайта!');
    }
}
