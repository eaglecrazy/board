<?php

namespace App\Console\Commands\Advert;

use App\Entity\Adverts\Advert\Advert;
use App\Usecases\Adverts\AdvertService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Entity\User;


class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    protected $description = 'The command removes expired ads.';

    private $service;

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $success = true;
        foreach (Advert::active()->where('expires_at', '<', Carbon::now())->cursor() as $advert) {
            try {
                $this->service->expire($advert);
            } catch (\DomainException $e) {
                $this->error($e->getMessage());
                $success = false;
            }
        }
        return $success;
    }
}
