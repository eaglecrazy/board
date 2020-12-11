<?php


namespace App\Usecases\Banners;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Http\Requests\Adverts\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;

class BannersSearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRandomForView(?int $categoryId, ?int $regionId, $format): ?Banner
    {
        $response = $this->client->search([
            'index' => 'banners',
            'type' => 'banner',
            'body' => [
                '_source' => ['id'],
//                'size' => 5,
//                'sort' => [
//                    '_script' => [
//                        'type' => 'number',
//                        'script' => 'Math.random() * 200000',
//                        'order' => 'asc',
//                    ],
//                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['status' => Banner::STATUS_ACTIVE]],
//                            ['term' => ['format' => $format ?: '']],
//                            ['term' => ['categories' => [$categoryId, 0] ?: 0]],
//                            ['term' => ['regions' => $regionId ?: 0]],
                        ],
                    ],
                ],
            ],
        ]);

        if(!$ids = array_column($response['hits']['hits'], '_id')){
            return null;
        }

        $banner = Banner::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
//            ->orderByRaw('FIELD(id' . implode(',', $ids) . ')')
            ->first();

        if(!$banner){
            return null;
        }

        $banner->view();
        return $banner;
    }
}
