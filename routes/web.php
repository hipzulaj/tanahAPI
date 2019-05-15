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

$router->get('/home', 'HomeController@index');
$router->get('/getsensor/{nama_tanaman}', 'HomeController@getSensorData');
$router->get('/getindicator/{nama_tanaman}', 'HomeController@indicator');
$router->get('/showec', 'DetailsController@showECLog');
$router->get('/showph', 'DetailsController@showPHLog');
$router->get('/showtemp', 'DetailsController@showTempLog');
$router->get('/showhumid', 'DetailsController@showHumidLog');
$router->post('/update', 'ArduinoController@updateCondition');
