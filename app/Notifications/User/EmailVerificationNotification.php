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
        return (new MailMessage)
            ->subject('Registracion is passed')
            ->greeting('Hello!')
            ->line('Your advert successfully passed a moderation.')
            ->action('View Advert', route('register.verify', ['token' => $this->user->verify_token]))
            ->line('Thank you for using our application!');
    }}
