<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'api/v1', 'namespace' => 'API\V1'], function () use ($router){

    $router->group(['prefix' => 'users'], function () use($router){

        $router->post('', 'UserController@store');

        $router->put('', 'UserController@updateInfo');

        $router->put('change-password', 'UserController@updatePassword');

        $router->delete('', 'UserController@delete');

        $router->get('', 'UserController@index');

    });

    $router->group(['prefix' => 'categories'], function () use($router){

        $router->post('', 'CategoryController@store');

        $router->delete('', 'CategoryController@delete');

        $router->put('', 'CategoryController@update');

    });

});
