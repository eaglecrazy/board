<?php

namespace App\Console\Commands\Search;

use App\Entity\Adverts\Advert\Advert;
//use App\Entity\Banner\Banner;
use App\Services\Search\AdvertIndexer;
//use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    private $advertsIndexer;
    private $banners;

//    public function __construct(AdvertIndexer $adverts, BannerIndexer $banners)
    public function __construct(AdvertIndexer $adverts)
    {
        parent::__construct();
        $this->advertsIndexer = $adverts;
//        $this->banners = $banners;
    }

    public function handle(): bool
    {
        $this->advertsIndexer->clear();

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->advertsIndexer->index($advert);
        }

//        $this->banners->clear();
//
//        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
//            $this->banners->index($banner);
//        }

        return true;
    }
}
