<?php

namespace Paksuco\DuskTimeTravel\Tests;

use Illuminate\Support\Carbon;
use Paksuco\DuskTimeTravel\CanChangeDuskTime;
use Orchestra\Testbench\Dusk\TestCase as BaseTestCase;
use Paksuco\DuskTimeTravel\Browser as TimeTravelEnabledBrowser;
use Paksuco\DuskTimeTravel\DuskTimeTravelServiceProvider;
use Paksuco\DuskTimeTravel\Middleware\ModifyDuskBrowserTime;

abstract class DuskTestCase extends BaseTestCase
{
    use CanChangeDuskTime;

    protected static $baseServeHost = 'localhost';
    protected static $baseServePort = 9516;

    protected function getPackageProviders($app)
    {
        return [
            DuskTimeTravelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->pushMiddleware();
        $this->registerTestRoute($app);
    }

    protected function pushMiddleware()
    {
        $kernel = $this->getKernel();
        $kernel->pushMiddleware(ModifyDuskBrowserTime::class);
    }

    protected function getKernel()
    {
        return app('Illuminate\Contracts\Http\Kernel');
    }

    protected function registerTestRoute($app)
    {
        $router = $app["router"];

        $router->get('time', [
            'middleware' => 'web',
            'uses' => function () {
                return Carbon::now()->startOfHour()->toIso8601String();
            },
        ]);
    }
}
