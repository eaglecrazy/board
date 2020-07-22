<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->word,
        'slug' => Str::slug($name),
        'parent_id' => null
    ];
});
