<?php

namespace App\Console\Commands\Search;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Banner\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;


class reindexCommand extends Command
{
    protected $signature = 'search:reindex';

    private $advertsIndexer;
    private $bannersIndexer;

    public function __construct(AdvertIndexer $adverts, BannerIndexer $banners)
    {
        parent::__construct();
        $this->advertsIndexer = $adverts;
        $this->bannersIndexer = $banners;
    }

    public function handle(): bool
    {
        $this->advertsIndexer->clear();

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->advertsIndexer->index($advert);
        }

        $this->bannersIndexer->clear();

//        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
//            $this->bannersIndexer->index($banner);
//        }

        return true;
    }
}
