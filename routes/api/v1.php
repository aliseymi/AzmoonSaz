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

        $router->get('', 'CategoryController@index');

    });

    $router->group(['prefix' => 'quizzes'], function () use($router){

        $router->get('', 'QuizController@index');

        $router->post('', 'QuizController@store');

        $router->put('', 'QuizController@update');

        $router->delete('', 'QuizController@delete');
    });

    $router->group(['prefix' => 'questions'], function () use($router){

        $router->post('', 'QuestionController@store');
    });

});
