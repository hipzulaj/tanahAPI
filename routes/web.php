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

//Non Auth User or Just Arduino
$router->post('/login', 'AuthController@loginPost');
$router->post('/update', 'ArduinoController@updateCondition');

//Home Routes
$router->get('/getindicator/{nama_tanaman}', 'HomeController@indicator');
$router->get('/refresh', 'HomeController@RefreshSensor');


//Settings Routes
//arduino
$router->post('/addsensors','ArduinoController@AddSensors');
$router->delete('/removesensor/{id}','ArduinoController@RemoveSensor');
$router->get('/listsensor', 'ArduinoController@ShowListSensors');
$router->get('/datasensor/{id}', 'ArduinoController@editSensor');
$router->post('/editsensor/{id}', 'ArduinoController@editSensorToDB');
$router->post('/updatenilai/{id}', 'ArduinoController@UpdateNilaiAfterEdit');
$router->post('updateallnilaibytanaman/{nama_tanaman}', 'HomeController@UpdateAllNilaiByTanaman');

//tanaman
$router->post('/addtanaman','SettingsController@AddNewTanamanToDB');
$router->delete('/removetanaman/{id}','SettingsController@RemoveTanaman');
$router->get('/listtanaman', 'SettingsController@showListTanaman');
$router->get('/datatanaman/{id}', 'SettingsController@editTanaman');
$router->post('/edittanaman/{id}', 'SettingsController@editTanamanToDB');

//Show Per Indicator
$router->get('/showec', 'DetailsController@showECLog');
$router->get('/showph', 'DetailsController@showPHLog');
$router->get('/showtemp', 'DetailsController@showTempLog');
$router->get('/showhumid', 'DetailsController@showHumidLog');

//User Management
$router->get('/usermanagement/listusers/', 'SettingsController@ShowUsers');
$router->delete('/usermanagement/removeuser/{id}', 'SettingsController@RemoveUsers');
$router->post('/usermanagement/makeadmin/{id}', 'SettingsController@MakeAsAdmin');
$router->post('/usermanagement/removeadmin/{id}', 'SettingsController@DeleteFromAdmin');

$router->group(['middleware' => 'jwt.auth'], function () use ($router){

    $router->get('/home', 'HomeController@Index');
    $router->get('/getsensor/{nama_alat}/{nama_tanaman}', 'HomeController@getSensorData');
    $router->delete('/removesensor/{id}','ArduinoController@RemoveSensor');

});


//Test and Debug
$router->get('/tes', 'HomeController@Index');
$router->get('/test/{nama_alat}/{nama_tanaman}', 'HomeController@getSensorData');
$router->get('/test/','ArduinoController@updateCondition');
$router->get('/push', 'HomeController@SendMessage');