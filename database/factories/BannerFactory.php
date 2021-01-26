<?php

use App\Entity\Banner\Banner;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;


/** @var \Illuminate\Database\Eloquent\Factory $factory */


$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $name = 'reg-' . $faker->unique()->city,
        'slug' => Str::slug($name),
        'parent_id' => null
    ];
});




$factory->define(Banner::class, function (Faker $faker) {

    $name = $faker->word();

    $statuses = array_keys(Banner::statusesList());
    $status = $statuses[rand(0, count($statuses) - 1)];

    $camerasCategoryId = Category::where('name', 'Фотоаппараты')->first()->id;
    $lensesCategoryId = Category::where('name', 'Объективы')->first()->id;
    $flashesCategoryId = Category::where('name', 'Вспышки')->first()->id;
    $categoriesIds = [$camerasCategoryId, $lensesCategoryId, $flashesCategoryId];
    $categoryId = $categoriesIds[rand(0, 2)];

    $moscow = Region::where('name', 'Москва')->first()->id;
    $spb = Region::where('name', 'Санкт-Петербург')->first()->id;
    $regionsIds = [$moscow, $spb];
    $regionId = $regionsIds[rand(0, 1)];

    $url = 'https://yandex.ru/search/?text=' . $name;
    $limit = rand(1,5) * 100;
    $format = '240x400';

    $files = removePublic(Storage::allFiles('./public/banners/test'));
    $file = $files[rand(0, count($files)-1)];

    $rejectReason = '';
    if($status == Banner::STATUS_DRAFT && rand(0,2) === 0){
        $rejectReason = $faker->text(50);
    }

    $publishedAt = null;
    if($status == Banner::STATUS_ACTIVE){
        $publishedAt = Carbon::now();
    }

    return [
        'name' => $name,
        'status' =>$status,
        'category_id' => $categoryId,
        'region_id' => $regionId,
        'url' => $url,
        'limit' => $limit,
        'format' => $format,
        'file' => $file,
        'reject_reason' => $rejectReason,
        'published_at' => $publishedAt,
        'views' => 0,
    ];
});


function removePublic(array $allFiles): array
{
    foreach ($allFiles as &$name) {
        $name = Str::replaceFirst('public/', '', $name);
    }
    return $allFiles;
}
