<?php

namespace App\Console\Commands\Seed\Regions;


use Illuminate\Console\Command;

class SeedRegions extends Command
{
    protected $signature = 'seed:regions';
    private $access_token = 'b8568bac94f8d60c68ece4a645584e0a93cd50cc6483558dcde3ee83092b848f4580282ed07ad954d6e1b';

    public function handle()
    {

        $rootRegions = $this->getRootRegions();

//        var_dump($rootRegions);
//        die();

        $areasAndCities = $this->getAreasAndCities();

        var_dump($areasAndCities);

        $areas = $this->getAreas($areasAndCities, $rootRegions);
//        var_dump($areas);

        $areasIds = $this->getAreasIds($areas);
        var_dump($areasIds);
        die();

        $cities = $this->getCities($areasAndCities, $areasIds);

    }

    private function getRootRegions()
    {
        $parameters = [
            'access_token' => $this->access_token,
            'v' => '5.21',
            'country_id' => 1,
        ];


        $url = 'https://api.vk.com/method/database.getRegions?' . http_build_query($parameters);
        $result = file_get_contents($url);
        $resultRoot = \GuzzleHttp\json_decode($result);

        $rootRegions = [];
        array_map(function ($region) use (&$rootRegions) {
            array_push($rootRegions, [
                'parent_id' => 'null',
                'name' => $region->title,
                'id' => $region->id,
            ]);
        }, $resultRoot->response->items);
        return $rootRegions;
    }

    private function getAreasAndCities()
    {
        $end = 10;
        $resultCities = [];

        for ($num = 0; $num < $end; $num++) {
            $access_token = $this->access_token;
            $parameters = [
                'access_token' => $access_token,
                'v' => '5.21',
                'country_id' => 1,
                'need_all' => 1,
                'count' => 1000,
                'offset' => $num * 1000,
            ];

            $url = 'https://api.vk.com/method/database.getCities?' . http_build_query($parameters);
            $result = file_get_contents($url);

            if (!isset(\GuzzleHttp\json_decode($result)->response)) {
                dd($result);
            }

            $end = \GuzzleHttp\json_decode($result)->response->count / 1000;
            $end = 2;
            echo 'Request № ' . ($num + 1) . ' of ' . ($end % 1000 + 1) . '.' . PHP_EOL;
            $resultCities = array_merge($resultCities, \GuzzleHttp\json_decode($result)->response->items);
            echo 'Regions count: ' . count($resultCities) . PHP_EOL;
            echo 'Last region:' . ($resultCities[count($resultCities) - 1])->title;
            echo PHP_EOL . PHP_EOL;
            usleep(200000);
        }

        return $resultCities;
    }

    private function getAreas(array $cities, array $rootRegions)
    {
        $areas = [];
        $areas_light = [];
        foreach ($cities as $city) {
            if (isset($city->area) && !in_array($city->area, $areas_light)) {
                $areas_light[] = $city->area;
                $areas[] = [
                    'id' => $city->id,
                    'name' => trim($city->area),
                    'region_name' => trim($city->region),
                    'parent_id' => $this->getAreaParentId(trim($city->region), $rootRegions),
                ];
            }
        }
        sort($areas);
        return $areas;
    }

    private function getAreaParentId(string $regionName, array $rootRegions)
    {
        foreach ($rootRegions as $region) {
            if ($region['name'] === $regionName)
                return $region['id'];
        }
    }

    private function getAreasIds($areas): array
    {
        $ids = [];
        foreach ($areas as $area) {
            $ids[] = $area['id'];
        }
        return $ids;
    }

    private function getCities(array $areasAndCities, array $areasIds): array
    {
        $cities = [];
        foreach ($areasAndCities as $city){

            if(!in_array($city->id, $areasIds)){
                //тогда это не область и его нужно вставить в города
            }
        }
    }


}
