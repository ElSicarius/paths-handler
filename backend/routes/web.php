<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Chemin Admin Aléatoire
|--------------------------------------------------------------------------
|
| On lit la variable ADMIN_PATH depuis .env, sinon on utilise "admin"
|
*/
$adminPath = env('ADMIN_PATH', 'admin');

// 1) Groupe "public" (pas d'auth) pour le login
$router->group(['prefix' => $adminPath], function () use ($router) {
    // Page de login + traitement
    $router->get('login', 'AdminController@showLoginForm');
    $router->post('login', 'AdminController@processLogin');
});

// 2) Groupe protégé par sessionAuth
$router->group(['prefix' => $adminPath, 'middleware' => 'sessionAuth'], function () use ($router) {

    // Page d’accueil admin
    $router->get('/', 'AdminController@index');

    // Logs
    $router->get('logs', 'AdminController@showLogs');
    // Nouveau : Clear all logs
    $router->delete('logs/clear', 'AdminController@clearAllLogs');

    // Page d'édition Endpoint
    $router->get('endpoints/{id}/edit', 'AdminController@editEndpoint');
    $router->get('endpoints', 'AdminController@listEndpoints');

    // CRUD des endpoints
    $router->post('endpoints', 'AdminController@createEndpoint');
    $router->put('endpoints/{id}', 'AdminController@updateEndpoint');
    $router->delete('endpoints/{id}', 'AdminController@deleteEndpoint');

    // Logout
    $router->get('logout', 'AdminController@logout');
});

// Catch-all route pour tout le reste
$router->get('{path:.*}', 'PathController@handle');

$router->post('{path:.*}', 'PathController@handle');

$router->put('{path:.*}', 'PathController@handle');

$router->delete('{path:.*}', 'PathController@handle');

$router->addRoute(['OPTIONS'], '{path:.*}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
        ->header('Access-Control-Allow-Headers', '*');
});
