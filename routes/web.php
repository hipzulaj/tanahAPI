<?php

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
    return $router->app->version();
});

$router->get('/home', 'ArduinoController@index');
$router->get('/showec', 'ArduinoController@showECLog');
$router->get('/showph', 'ArduinoController@showPHLog');
$router->get('/showtemp', 'ArduinoController@showTempLog');
$router->get('/showhumid', 'ArduinoController@showHumidLog');
$router->post('/update', 'ArduinoController@updateCondition');
