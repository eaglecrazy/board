<?php


namespace App\Usecases\Auth;

use App\Entity\User\SocialNetwork;
use App\Entity\User\User;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthBySocialNetworkService
{
    public function auth(string $socialNetwork, SocialiteUser $socialNetworkUserData): User
    {
        $socialId = $socialNetworkUserData->getId();
        $email = $socialNetworkUserData->getEmail();

        if ($user = User::bySocialNetwork($socialNetwork, $socialId)->first()) {
            return $user;
        }

        if ($email && User::where('email', $email)->exists()) {
            throw new DomainException('Пользователь с email привязанным к этому аккаунту уже зарегистрирован.');
        }

        $user = User::registerBySocialNetwork($socialNetwork, $socialId);

        event(new Registered($user));

        return $user;
    }

    //метод запускается только если юзер уже аутентифицирован
    public function attach(string $socialNetwork, SocialiteUser $userData): void
    {
        if (User::bySocialNetwork($socialNetwork, $userData->getId())
            ->where('id', '<>', Auth::id())
            ->exists()) {
            throw new DomainException('Этот пользователь ' . $socialNetwork . ' уже зарегистрирован и аутентифицирован.');
        }
        //если это та же самая соцсеть
        if (User::bySocialNetwork($socialNetwork, $userData->getId())->exists()) {
            throw new DomainException('Этот пользователь ' . $socialNetwork . ' уже зарегистрирован и аутентифицирован.');
        }

        //если это какая то другая соцсеть
        /** @var User $user */
        Auth::user()->attachSocialNetwork($socialNetwork, $userData->getId());
    }
}
