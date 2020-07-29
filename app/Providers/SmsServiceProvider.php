<?php


namespace App\Providers;


use App\Services\Sms\FakeSender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsSender::class, function(Application $app){

            $config = $app->make('config')->get('sms');
            switch ($config['driver']){
                case 'sms.ru' :
                    $params = $config['driver']['sms.ru'];
                    if(!empty($cparams['url'])){
                        return new SmsRu($params['api_id'], $params['url']);
                    }
                    return new SmsRu($params['api_id']);
                case 'array' :
                    return new FakeSender();
                default :
                    throw new \InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });
    }
}
