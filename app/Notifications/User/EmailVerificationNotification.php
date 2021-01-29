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

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $url = route('register.verify', $this->user->verify_token);
        echo $url . PHP_EOL;
        return (new MailMessage)
            ->subject('Регистрация пройдена!')
            ->greeting('Здравствуйте, ' . $this->user->name)
            ->greeting('Здравствуйте!')
            ->line('Регистрация на Фотобарахолке №1 пройдена. Для подтверждения почты нажимте на кнопку ниже.')
            ->action('Подтвердить почту', $url)
            ->line('Спасибо за использование нашего сайта!');
    }
}
