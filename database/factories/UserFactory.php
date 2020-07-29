<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;


$factory->define(User::class, function (Faker $faker) {
    $active = $faker->boolean;
    $phoneActive = $faker->boolean;
    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
//        'email_verified_at' => now(),
        'phone' => $faker->unique()->phoneNumber,
        'phone_verified' => $phoneActive,
        'phone_verify_token' => $phoneActive ? null : (string)random_int(10000, 99999),
        'phone_verify_token_expire' =>  $phoneActive ? null : Carbon::now()->addSeconds(User::PHONE_VERIFY_TIME),
        'phone_auth' => !$phoneActive ? false : $faker->boolean,
//        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'password' => '$2y$10$JB9ek7frDjM3sPmb8uEsf.dUtDRs1ePCpJ1E6B7WCXfhwZwI54P3G', // 123
        'remember_token' => Str::random(10),
        'verify_token' => $active ? null : Str::uuid(),
        'status' => $active ? User::STATUS_ACTIVE : User::STATUS_WAIT,
        'role' => $active ? $faker->randomElement([User::ROLE_ADMIN, User::ROLE_USER]) : User::ROLE_USER,
    ];
});
