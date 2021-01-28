<?php

namespace App\Jobs\Advert;

use App\Entity\Adverts\Advert\Advert;
use App\Notifications\Advert\ModerationPassedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ModerationPassedNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }


    public function handle()
    {
        $this->advert->user->notify(new ModerationPassedNotification($this->advert));
    }
}
