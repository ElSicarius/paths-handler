<?php

namespace App;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Http\Middleware\LogRequestMiddleware;

class Kernel extends ConsoleKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables::class,
        \Laravel\Lumen\Bootstrap\LoadConfiguration::class,
        \Laravel\Lumen\Bootstrap\LoadTranslations::class,
        \Laravel\Lumen\Bootstrap\LoadRoutes::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
    ];

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        LogRequestMiddleware::class,
    ];

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
    }
}
