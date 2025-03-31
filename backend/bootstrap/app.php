<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// Active Eloquent (si nécessaire).
$app->withEloquent();

// Active le composant de vues
$app->register(Illuminate\View\ViewServiceProvider::class);


// Configure le répertoire des vues (facultatif, par défaut c'est resources/views)
$app->configure('view');

// On s'assure que storage_path pointe vers storage
$app->bind('path.storage', function () {
    return base_path('storage');
});

$app->singleton(
    Illuminate\Contracts\View\Factory::class,
    function ($app) {
        return $app->make('view');
    }
);

// Associer le Console Kernel (pour `artisan migrate` etc.)
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

// Associer le Handler d'exception
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Enregistrement des middlewares globaux
|--------------------------------------------------------------------------
|
| On ajoute le middleware RequestLoggerMiddleware pour logger toutes
| les requêtes (sauf admin), et StartSessionMiddleware pour démarrer
| une session PHP sur chaque requête.
|
*/
$app->middleware([
    App\Http\Middleware\RequestLoggerMiddleware::class,
    App\Http\Middleware\StartSessionMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Middlewares de route
|--------------------------------------------------------------------------
|
| On définit un middleware "sessionAuth" qui protégera les routes admin.
| Il vérifiera la session pour voir si l'utilisateur est logué.
|
*/
$app->routeMiddleware([
    'sessionAuth' => App\Http\Middleware\SessionAuthMiddleware::class,
]);

// Charger les routes
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
