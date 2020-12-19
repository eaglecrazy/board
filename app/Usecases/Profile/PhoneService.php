<?php


namespace App\Usecases\Profile;


use App\Entity\User\User;
use App\Services\Sms\SmsSender;
use Carbon\Carbon;

class PhoneService
{
    private SmsSender $smsService;

    public function __construct(SmsSender $sms)
    {
        $this->smsService = $sms;
    }

    public function sendPhoneVerifyToken(User $user)
    {
            $token = $user->requestPhoneVerification(Carbon::now());
            $this->smsService->send($user->phone, 'Токен верификации: ' . $token);
    }

    public function checkVerifyToken(User $user, string $token)
    {
        $user->verifyPhone($token, Carbon::now());
    }

    public function togglePhoneAuth(User $user)
    {
        $user->update(['phone_auth' => !$user->phone_auth]);
    }
}
