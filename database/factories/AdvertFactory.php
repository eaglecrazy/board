<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Region;
use App\Entity\Adverts\Category;
use App\Entity\User\User;
use Carbon\Carbon;
use Faker\Generator as Faker;


$factory->define(Advert::class, function (Faker $faker) {
    $userId = User::All()->random()->id;
    $category = Category::All()->random();
    $lens = false;
    if($category->name === 'Объективы'){
        $lens = true;
    }
    $categoryId = $category->id;
    $mosSpb = rand(1, 2);
    $regionId = $mosSpb;
    $brands = ["Canon", "Nikon", "Sony"];
    if($lens) {
        $title = $brands[rand(0, 2)] . ' ' . Str::ucfirst($faker->word()) . ' ' . rand(10, 200) . 'mm';
    } else {
        $title = $brands[rand(0, 2)] . ' ' . Str::ucfirst($faker->word()) . ' ' . rand(1, 10000);
    }
    $price = rand(1, 200) * 1000;
    $adress = $mosSpb === 1 ? 'Москва' : 'Санкт-Петербург';
    $content = $faker->text(200) . PHP_EOL . $faker->text(200);
    $status = getRandomAdvertStatus();
    if ($status === Advert::STATUS_DRAFT && rand(0, 3) === 3) {
        $reject_reason = $faker->text(20);
    } else {
        $reject_reason = null;
    }
    if ($status === Advert::STATUS_ACTIVE) {
        $published = Carbon::now()->subDay(rand(1, 15));
        $expires = Carbon::now()->addDay(rand(1, 15));
    } else {
        $published = null;
        $expires = null;
    }
    $GLOBALS['advert_seeder']--;
    if($GLOBALS['advert_seeder'] % 1000 === 0){
        echo ($GLOBALS['advert_seeder']) . ' adverts left.' . PHP_EOL;
    }

    return [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'region_id' => $regionId,
            'title' => $title,
            'price' => $price,
            'address' => $adress,
            'content' => $content,
            'status' => $status,
            'reject_reason' => $reject_reason,
            'published_at' => $published,
            'expires_at' => $expires,
        ];
});


function getRandomAdvertStatus()
{
    $statuses = [
        Advert::STATUS_DRAFT,
        Advert::STATUS_MODERATION,
        Advert::STATUS_CLOSED
    ];
    $statusValue = rand(0, 10);
    if ($statusValue > 2) {
        $status = Advert::STATUS_ACTIVE;
    } else {
        $status = $statuses[$statusValue];
    }
    return $status;
}


