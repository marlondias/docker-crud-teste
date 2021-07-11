<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'Você está na API do CRUD de desenvolvedores Gazin. Acesse "GET /api/developers" para mais ações.';
});

$router->group(['prefix' => 'api/developers', 'as' => 'developers'], function() use ($router) {
    $router->get('/', 'DeveloperController@index');
    $router->post('/', 'DeveloperController@store');
    $router->get('/{id}', 'DeveloperController@show');
    $router->put('/{id}', 'DeveloperController@update');
    $router->delete('/{id}', 'DeveloperController@destroy');
});

