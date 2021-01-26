<?php

namespace App\Usecases\Banners;

use Illuminate\Contracts\Pagination\Paginator;

class BannersSearchResult
{
    public $banners;
    public $regionsCounts;
    public $categoriesCounts;

    public function __construct(Paginator $banners, array $regionsCounts, array $categoriesCounts)
    {
        $this->banners = $banners;
        $this->regionsCounts = $regionsCounts;
        $this->categoriesCounts = $categoriesCounts;
    }
}
