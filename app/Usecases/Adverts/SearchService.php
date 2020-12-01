<?php


namespace App\Usecases\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Adverts\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(
        ?Category $category,
        ?Region $region,
        SearchRequest $request,
        int $perPage,
        int $page): SearchResult
    {
        // получаем из реквеста только заполненные поля
        $values = $this->getAttributesArray($request);


        $query = [
            'index' => 'adverts',
            'type' => 'advert',
            'body' => [
                '_source' => ['id'],
                //размеры выборки
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                //сортирвка по дате публикации нужна только если поиск был не по тексту
                'sort' => empty($request['text']) ? ['published_at' => ['order' => 'desc']] : [],
                //сам запрос
                'query' => [
                    'bool' => [
                        'must' => $this->advertsMust($category, $region, $request, $values)
                    ],
                ],
                'aggs' => $this->agregations(),
            ],
        ];
//        dd($query);

        $responce = $this->client->search($query);

//        dd($responce['aggregations']);
//        dd($responce['hits']);

        $regionsCounts = array_column($responce['aggregations']['group_by_region']['buckets'], 'doc_count', 'key');
        $categoryesCounts = array_column($responce['aggregations']['group_by_category']['buckets'], 'doc_count', 'key');
        $ids = array_column($responce['hits']['hits'], '_id');

        //        dd($ids);

        if ($ids) {
            $items = Advert::active()
                ->with(['category', 'region'])
                ->whereIn('id', $ids)
                //ларавел по умолчанию считает FIELD.... именем поля, поэтому нужно добавить Expression,
                // чтобы элоквент понял, что это фрагмент запроса и не экранировал его
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
            $paginator = new LengthAwarePaginator($items, $responce['hits']['total'], $perPage, $page);
        }
        else {
            $paginator = new LengthAwarePaginator([], 0, $perPage, $page);
        }

        return new SearchResult($paginator, $regionsCounts, $categoryesCounts);
    }

    private function advertsMust(?Category $category, ?Region $region, SearchRequest $request, array $values): array
    {
        //отображать только активные объявления
        $term = ['term' => ['status' => Advert::STATUS_ACTIVE]];

        //если категория или регион не заданы, то присвоим элементам массива false
        $categoryRegion = array_filter([
            $category ? ['term' => ['categories' => $category->id]] : false,
            $region ? ['term' => ['regions' => $region->id]] : false,
        ]);

//        dd($categoryRegion);

        //если есть текст, то ищем в названиях и контенте, названия более релевантны (вес 3)
        $text = [];
        if (!empty($request['text'])) {
            $text = [
                'multi_match' => [
                    'query' => $request['text'],
                    'fields' => ['title^3', 'content']
                ]
            ];
        }

        //в array_map мы передаём значения values, а также массив ключей $values
        //и они обрабатываются одновременно
        //для каждого атрибута строится нестед в котором хранится id и один из параметров: equals, from, to
        $attributes = array_map(function ($value, $id) {
            return [
                'nested' => [
                    'path' => 'values',
                    'query' => [
                        'bool' => [
                            'must' => $this->attributeMust($value, $id)
                        ],
                    ],
                ],
            ];
        }, $values, array_keys($values));
//        dd(array_filter(array_merge([$term], $categoryRegion, [array_merge($text, $attributes)])));
        return array_filter(array_merge([$term], $categoryRegion, [array_merge($text, $attributes)]));
    }

    private function attributeMust($value, $id): array
    {
        $id = ['match' => ['values.attribute' => $id]];
        $equals = !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false;
        $from = !empty($value['from']) ? ['range' => ['values.value_int' => ['gte' => $value['from']]]] : false;
        $to = !empty($value['to']) ? ['range' => ['values.value_int' => ['lte' => $value['to']]]] : false;

        //в результирующем массиве ключи не нужны, они только всё испортят далее, поэтому array_values
        return array_values(array_filter([$id, $equals, $from, $to]));
    }

    private function getAttributesArray(SearchRequest $request): array
    {
        // ключ - идентификаторы поиска, в форме они будут такие:
        // name="attr[6][equals]" / name="attr[6][from]" / name="attr[6][to]"

        //получим данные и приведём к массиву, на случай если форма не была заполнена
        $values = (array)$request->input('attrs');

        //отфильтруем незаполненные инпуты и вернём массив с ними
        return array_filter($values, function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });
    }

    private function agregations(): array
    {
        return
            [
                'group_by_region' =>
                    [
                        'terms' =>
                            [
                                'field' => 'regions'
                            ],
                    ],
                'group_by_category' =>
                    [
                        'terms' =>
                            [
                                'field' => 'categories'
                            ],
                    ],
            ];
    }
}
