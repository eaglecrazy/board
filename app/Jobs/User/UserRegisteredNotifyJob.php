<?php

namespace App\Jobs\User;

use App\Entity\User\User;
use App\Notifications\User\EmailVerificationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserRegisteredNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        //почему то в уведомлении route('register.verify', $this->user->verify_token) генерирует вместо домена localhost.
        //поэтому передаём урл отсюда
        $url = route('register.verify', $this->user->verify_token);
        dd($url);
        $this->user->notify(new EmailVerificationNotification($this->user, $url));
    }
}
