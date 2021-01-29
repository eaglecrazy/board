<?php

namespace App\Usecases\Adverts;

use Illuminate\Contracts\Pagination\Paginator;

class AdvertsSearchResult
{
    public $adverts;
    public $regionsCounts;
    public $categoriesCounts;

    public function __construct(Paginator $adverts, array $regionsCounts, array $categoriesCounts)
    {
        $this->adverts = $adverts;
        $this->regionsCounts = $regionsCounts;
        $this->categoriesCounts = $categoriesCounts;
    }
}
