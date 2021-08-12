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

$api_version = 'v1.0';

// Public Web Routes
$router->get('/', function () use ($router) {
    return env('APP_NAME') . '<BR>' . $router->app->version();
});

// Auth API ROUTES
$router->group(['prefix' => 'api/' . $api_version], function () use ($router) {
    $router->post('register',  ['uses' => 'AuthController@register', 'as' => 'register']);
    $router->post('login',  ['uses' => 'AuthController@login', 'as' => 'login']);
});

// TODO Notes ROUTES
$router->group(['prefix' => 'api/' . $api_version, 'middleware' => 'auth'], function () use ($router) {
    $router->get('todo-notes[/{user_id:[0-9]+}]', ['uses' => 'TodoNoteController@index', 'as' => 'todo-note.index']);
    $router->post('todo-notes', ['uses' => 'TodoNoteController@store', 'as' => 'todo-note.store']);
    $router->patch('todo-notes/{id:[0-9]+}', ['uses' => 'TodoNoteController@update', 'as' => 'todo-note.update']);
    $router->delete('todo-notes/{id:[0-9]+}', ['uses' => 'TodoNoteController@destroy', 'as' => 'todo-note.destroy']);
});
