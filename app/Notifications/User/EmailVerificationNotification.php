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
        dd(route('register.verify', $this->user->verify_token));

        return (new MailMessage)
            ->subject('Регистрация пройдена!')
            ->greeting('Здравствуйте, ' . $this->user->name)
            ->greeting('Здравствуйте!')
            ->line('Регистрация на Фотобарахолке №1 пройдена. Для подтверждения почты нажимте на кнопку ниже.')
//            ->action('Подтвердить почту', 'http://board.xyz/verify/' . $this->user->verify_token)
            ->action('Подтвердить почту', route('register.verify', $this->user->verify_token))
            ->line('Спасибо за использование нашего сайта!');
    }
}
