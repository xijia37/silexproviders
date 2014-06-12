<?php

namespace Hisu\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
//use Cat\Bridge\Doctrine\Logger\DbalLogger;

class MedooServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['databases'] = $app->share(function ($app) {
            $databases = new \Pimple();
            foreach ($app['databases.options'] as $name => $option) {
                $databases[$name] = $databases->share(function ($app) use($option){
                    return new \medoo(array_replace([
                        'database_type' => 'mysql',
                        'server' => 'localhost',
                        'username' => 'root',
                        'password' => ''
                    ], $option));
                });
                if (!isset($app['databases.default'])) {
                    $app['databases.default'] = $name;
                }
//                $app['databases.default'] = $app['databases.default']?:$name;
            }
            return $databases;
        });
        $app['database'] = $app->share(function ($app) {
            $databases = $app['databases'];
            return $databases[$app['databases.default']];
        });
    }

    public function boot(Application $app)
    {
    }
}
