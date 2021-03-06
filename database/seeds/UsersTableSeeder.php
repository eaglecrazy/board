<?php

use Illuminate\Database\Seeder;
use App\Entity\User\User ;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::where('name', '!=', '')->delete();

        factory(User::class, 150)->create();

        User::create([
            'name' => 'eagle',
            'last_name' => 'falcon',
            'email' => 'eaglezzzzz@rambler.ru',
            'phone' => '79046495737',
            'phone_verified' => true,
            'phone_verify_token' => null,
            'phone_verify_token_expire' => null,
            'phone_auth' => false,
            'password' => '$2y$10$JB9ek7frDjM3sPmb8uEsf.dUtDRs1ePCpJ1E6B7WCXfhwZwI54P3G',
            'remember_token' => Str::random(10),
            'verify_token' => null,
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
