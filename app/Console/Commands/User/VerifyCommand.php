<?php

namespace App\Console\Commands\User;

use App\Usecases\Auth\RegisterService;
use Illuminate\Console\Command;
use App\Entity\User;

class VerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';

    protected $description = 'Verifing user';

    private $service;

    function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('Undefined email ' . $email);
        }

        try {
            $this->service->verify($user->id);
        } catch (\Exception $e) {
            $this->error('User ' . $email . ' already verified.');
            return false;
        }

        $this->info('User ' . $email . ' verified.');
        return true;
    }
}
