<?php

namespace App\Console\Commands\Seed\Regions;


use Illuminate\Console\Command;
use Storage;
use App\Entity\Region;
use Str;

class SeedRegions extends Command
{
    protected $signature = 'seed:regions';
    private $access_token = 'c2ae6d76af4dc42542c2b1d61b116c9f55ccf5ef0d5699a0604ae5930afd751aab45be2fd32626bb09ecd';
    private $id = 1;
    private $all;
    private $places = [];
    private $regionNames = [];


    public function handle()
    {
        $this->madeRegionNames();//впадлу руками делать то

// ПОЛУЧЕНИЕ ВСЕХ ДАННЫХ ИЗ ВК
//        $all = $this->getAreasAndCities();
//        $str = \GuzzleHttp\json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//        $rez = Storage::disk('public')->put('areasAndCities', $str);


// ПОЛУЧЕНИЕ ВСЕХ ДАННЫХ ИЗ ФАЙЛА
        $this->all = $this->getAreasAndCitiesFromFile();
        $this->trimAllNames();
        $this->sortAllPlaces();

        $regions = $this->getRegions();
        $data = $this->fillData($regions);
        $data = $this->changeRegionNames($data);
        $this->fillBD($data);

        echo 'Make elasticsearch.' . PHP_EOL;
        $this->call('search:init');
    }

    private function fillData(array $regions): array
    {
        echo 'Filling data.' . PHP_EOL;
        $data = [];
        $count = count($regions);
        $num = 1;

        foreach ($regions as $region) {
            echo 'Filling region: ' . $num++ . ' of ' . $count . ' ' . $region['name'] . PHP_EOL;
            $data[] = $region;

            //добавляем области/районы
            if (empty($this->places[$region['name']])) {
                continue;
            }
            $areas = array_unique(array_keys($this->places[$region['name']]));
            $currentRegionAreaSlugs = [];//этот массив нужен из за того, что появляются одинаковые слаги.
            $currentRegionAreaNames = [];//этот массив нужен из за того, что появляются одинаковые имена.

            foreach ($areas as $areaName) {
                //улучшим имена районов/областей
                $areaName = $this->getNiceAreaName($areaName);

                //нельзя, чтобы имена повторялись в пределах области
                if (!(array_search(Str::lower($areaName), $currentRegionAreaNames) !== false)) {
                    $currentRegionAreaNames[] = Str::lower($areaName);

                    //нельзя, чтобы слаги повторялись в пределах области
                    $slug = Str::slug($areaName);
                    while (array_search($slug, $currentRegionAreaSlugs) !== false) {
                        $slug = $slug . Str::lower(Str::random(1));
                    }
                    $currentRegionAreaSlugs[] = $slug;

                    //добавим область
                    if ($areaName !== 'without-area') {
                        $data[] = [
                            'id' => $areaId = $this->id++,
                            'name' => $areaName,
                            'parent_id' => $region['id'],
                            'slug' => $slug,
                        ];
                    }
                }
                //добавляем города/дерёвни
                if (empty($this->places[$region['name']][$areaName])) {
                    continue;
                }

                $currentAreaCitySlugs = [];//этот массив нужен из за того, что появляются одинаковые слаги.
                $currentAreaCityNames = [];//этот массив нужен из за того, что появляются одинаковые имена.
                $cities = array_unique($this->places[$region['name']][$areaName]);

                foreach ($cities as $cityName) {

                    //нельзя, чтобы имена повторялись в пределах области
                    if (
                        (array_search(Str::lower($cityName), $currentAreaCityNames) !== false) ||
                        ($areaName === 'without-area' && (array_search(Str::lower($cityName), $currentRegionAreaNames) !== false))
                    ) {
                        continue;
                    }
                    $currentAreaCityNames[] = Str::lower($cityName);

                    //нельзя, чтобы слаги повторялись в пределах области
                    $slug = Str::slug($cityName);
                    while ((array_search($slug, $currentAreaCitySlugs) !== false)) {
                        $slug = $slug . Str::lower(Str::random(1));
                    }
                    $currentAreaCitySlugs[] = $slug;

                    if ($areaName !== 'without-area') {
                        $data[] = [
                            'id' => $this->id++,
                            'name' => $cityName,
                            'parent_id' => $areaId,
                            'slug' => $slug,
                        ];
                    } else {
                        $data[] = [
                            'id' => $this->id++,
                            'name' => $cityName,
                            'parent_id' => $region['id'],
                            'slug' => $slug,
                        ];
                    }
                }
            }
        }
        echo 'Filling data is done.' . PHP_EOL;
        return $data;
    }

    private function getRegions(): array
    {
        $parameters = [
            'access_token' => $this->access_token,
            'v' => '5.21',
            'country_id' => 1,
        ];

        $url = 'https://api.vk.com/method/database.getRegions?' . http_build_query($parameters);
        $result = file_get_contents($url);
        $resultRoot = \GuzzleHttp\json_decode($result);

        $rootRegions =
            [
                [
                    'id' => $this->id++,
                    'name' => $mos = 'Москва город',
                    'slug' => Str::slug($mos),
                    'parent_id' => null,
                ],

                [
                    'id' => $this->id++,
                    'name' => $spb = 'Санкт-Петербург город',
                    'parent_id' => null,
                    'slug' => Str::slug($spb),
                ],
                [
                    'id' => $this->id++,
                    'name' => $sev = 'Севастополь город',
                    'parent_id' => null,
                    'slug' => Str::slug($sev),
                ]];

        array_map(function ($region) use (&$rootRegions) {
            array_push($rootRegions, [
                'id' => $this->id++,
                'parent_id' => null,
                'name' => $name = trim($region->title),
                'slug' => Str::slug($name),
            ]);
        }, $resultRoot->response->items);

        return $rootRegions;
    }

    private function getAreasAndCitiesFromFile()
    {
        $str = Storage::disk('public')->get('all');
        return \GuzzleHttp\json_decode($str, false, 512, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function sortAllPlaces()
    {
        $count = (count($this->all));
        $num = 0;

        echo 'Sorting 1:' . PHP_EOL;
        foreach ($this->all as $some) {
            if (++$num % 10000 === 0) {
                echo 'Sorted ' . $num . ' of ' . $count . PHP_EOL;
            }
            if (!(isset($some->region) && isset($some->area) && isset($some->title))) {
                continue;
            }
            if (empty($this->places[$some->region][$some->area])) {
                $this->places[$some->region][$some->area] = [];
            }

            $this->places[$some->region][$some->area][] = $some->title;
        }
        echo 'Sorting 1 is done.' . PHP_EOL;


        $num = 0;
        echo 'Sorting 2:' . PHP_EOL;
        foreach ($this->all as $some) {
            if (++$num % 10000 === 0) {
                echo 'Sorted ' . $num . ' of ' . $count . PHP_EOL;
            }

            if (!(isset($some->region) && empty($some->area) && isset($some->title))) {
                continue;
            }

            $this->places[$some->region]['without-area'][] = $some->title;
        }
        echo 'Sorting 2 is done.' . PHP_EOL;
    }

    private function trimAllNames()
    {
        echo 'Trimming:' . PHP_EOL;

        $count = (count($this->all));
        $num = 0;

        foreach ($this->all as $some) {
            if (isset($some->region)) {
                $some->region = trim($some->region);
            }
            if (isset($some->area)) {
                $some->area = trim($some->area);
            }
            if (isset($some->title)) {
                $some->title = trim($some->title);
            }
            $num++;
            if ($num % 10000 === 0) {
                echo 'Trimmed record № ' . $num . ' of ' . $count . PHP_EOL;
            }
        }
        echo 'Trimming is done.' . PHP_EOL;
    }

    private function fillBD(array $data)
    {

        echo 'Cleaning DB.' . PHP_EOL;
        Region::where('parent_id', null)->delete();

        echo 'Seedeng DB.' . PHP_EOL;
        $chunks = array_chunk($data, 1000);
        $count = count($chunks) * 1000;
        $num = 0;

        foreach ($chunks as $chunk) {
            if ($num % 10000 === 0) {
                echo 'Seeding ' . $num . ' of ' . $count . PHP_EOL;
            }
            Region::insert($chunk);
            $num += 1000;
        }
        echo 'Seedeng DB is done.' . PHP_EOL;
    }

    private function madeRegionNames()
    {
        $orig = explode(PHP_EOL, $this->originalRegionsNames);
        $modif = explode(PHP_EOL, $this->modifiedRegionsNames);

        for ($i = 0; $i < count($orig); $i++) {
            $this->regionNames[$orig[$i]] = $modif[$i];
        }
    }


    private $originalRegionsNames =
        'Адыгея
Алтай
Алтайский край
Амурская область
Архангельская область
Астраханская область
Башкортостан
Белгородская область
Брянская область
Бурятия
Владимирская область
Волгоградская область
Вологодская область
Воронежская область
Дагестан
Еврейская АОбл
Забайкальский край
Ивановская область
Ингушетия
Иркутская область
Кабардино-Балкарская
Калининградская область
Калмыкия
Калужская область
Камчатский край
Карачаево-Черкесская
Карелия
Кемеровская область
Кировская область
Коми
Корякский АО
Костромская область
Краснодарский край
Красноярский край
Крым
Курганская область
Курская область
Ленинградская область
Липецкая область
Магаданская область
Марий Эл
Мордовия
Москва город
Московская область
Мурманская область
Ненецкий АО
Нижегородская область
Новгородская область
Новосибирская область
Омская область
Оренбургская область
Орловская область
Пензенская область
Пермский край
Приморский край
Псковская область
Ростовская область
Рязанская область
Самарская область
Санкт-Петербург город
Саратовская область
Саха /Якутия/
Сахалинская область
Свердловская область
Севастополь город
Северная Осетия — Алания
Смоленская область
Ставропольский край
Таймырский (Долгано-Ненецкий) АО
Тамбовская область
Татарстан
Тверская область
Томская область
Тульская область
Тыва
Тюменская область
Удмуртская
Ульяновская область
Хабаровский край
Хакасия
Ханты-Мансийский Автономный округ - Югра АО
Челябинская область
Чеченская
Чувашская
Чукотский АО
Ямало-Ненецкий  АО
Ярославская область';

    private $modifiedRegionsNames =
        'республика Адыгея
республика Алтай
Алтайский край
Амурская область
Архангельская область
Астраханская область
республика Башкортостан
Белгородская область
Брянская область
республика Бурятия
Владимирская область
Волгоградская область
Вологодская область
Воронежская область
республика Дагестан
Еврейская АО
Забайкальский край
Ивановская область
республика Ингушетия
Иркутская область
Кабардино-Балкарская республика
Калининградская область
республика Калмыкия
Калужская область
Камчатский край
Карачаево-Черкесская республика
республика Карелия
Кемеровская область (Кузбасс)
Кировская область
республика Коми
Корякский АО
Костромская область
Краснодарский край
Красноярский край
республика Крым
Курганская область
Курская область
Ленинградская область
Липецкая область
Магаданская область
республика Марий Эл
республика Мордовия
Москва
Московская область
Мурманская область
Ненецкий АО
Нижегородская область
Новгородская область
Новосибирская область
Омская область
Оренбургская область
Орловская область
Пензенская область
Пермский край
Приморский край
Псковская область
Ростовская область
Рязанская область
Самарская область
Санкт-Петербург
Саратовская область
республика Саха (Якутия)
Сахалинская область
Свердловская область
Севастополь
республика Северная Осетия
Смоленская область
Ставропольский край
Таймырский (Долгано-Ненецкий) АО
Тамбовская область
республика Татарстан
Тверская область
Томская область
Тульская область
республика Тыва
Тюменская область
Удмуртская республика
Ульяновская область
Хабаровский край
республика Хакасия
Ханты-Мансийский Автономный округ (Югра)
Челябинская область
Чеченская республика
Чувашская республика
Чукотский АО
Ямало-Ненецкий АО
Ярославская область';

    private function changeRegionNames(array $data)
    {
        foreach ($data as &$some) {
            if (empty($some['parent_id'])) {
                $some['name'] = $this->regionNames[$some['name']];
            }
        }
        return $data;
    }

    private function getNiceAreaName($areaName): string
    {
        if ((Str::contains($areaName, 'Городской округ') || Str::contains($areaName, 'городской округ')) && Str::contains($areaName, ' район')) {
            $areaName = Str::replaceLast(' район', '', $areaName);
        }

        if (Str::contains($areaName, ' город')) {
            $areaName = Str::replaceLast(' город', '', $areaName);
        }
        return $areaName;
    }
}
