<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'api/v1', 'namespace' => 'API\V1'], function () use ($router){

    $router->group(['prefix' => 'users'], function () use($router){

        $router->post('', 'UserController@store');

    });

});
