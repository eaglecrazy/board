<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Region;
use Faker\Generator as Faker;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->city,
        'slug' => Str::slug($name),
        'parent_id' => null
    ];
});
