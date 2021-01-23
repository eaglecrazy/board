<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Entity\User\User ;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;


$factory->define(User::class, function (Faker $faker) {
    $emailVerifyed = $faker->boolean;
    //телефон может быть только после подтверждения почти
    $hasPhone = $emailVerifyed ? $faker->boolean : false;
    //запросить подтверждение можно только если есть телефон
    $requestVefifyPhone = $hasPhone ? $faker->boolean : false;
    //подтверждённый телефон может быть только если есть телефон и нет запроса
    $phoneVerifyed = ($hasPhone && !$requestVefifyPhone) ? $faker->boolean : false;

    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $hasPhone ? $faker->unique()->phoneNumber : null,
        'phone_verified' => $phoneVerifyed,
        'phone_verify_token' => $requestVefifyPhone ? (string)random_int(10000, 99999) : null,
        'phone_verify_token_expire' =>  $requestVefifyPhone ? Carbon::now()->addSeconds(User::PHONE_VERIFY_TIME) : null,
        'phone_auth' => $phoneVerifyed ? $faker->boolean : false,
//        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'password' => '$2y$10$JB9ek7frDjM3sPmb8uEsf.dUtDRs1ePCpJ1E6B7WCXfhwZwI54P3G', // 123
        'remember_token' => Str::random(10),
        'verify_token' => $emailVerifyed ? null : Str::uuid(),
        'status' => $emailVerifyed ? User::STATUS_ACTIVE : User::STATUS_WAIT,
        'role' => $emailVerifyed ? $faker->randomElement([User::ROLE_ADMIN, User::ROLE_USER]) : User::ROLE_USER,
    ];
});
