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
    private $url;

    public function __construct(User $user, string $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function handle()
    {
        $this->user->notify(new EmailVerificationNotification($this->user, $this->url));
    }
}
