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

// $router->get('/scanners', 'ScannerController@browse');
// $router->post('/scanners', 'ScannerController@add');
// $router->delete('/scanners/{id}', 'ScannerController@delete');
// $router->get('/scanners/{id}', 'ScannerController@show');
// $router->get('/scanners/{id}/errors', 'ScannerController@errorDetail');
// $router->get('/scanners/{id}/messages', 'ScannerController@messageDetail');
// $router->get('/scanners/{id}/issues', 'ScannerController@issueDetail');

//Scanner URL
$router->post('/launch', 'ScannerController@launchScanner');
$router->get('/scanners', 'ScannerController@browseScanner');
$router->get('/scanners/{id}', 'ScannerController@findScanner');
$router->get('/sync', 'ScannerController@resultSave');
$router->get('/sync-scanner', 'ScannerController@syncScanner');
$router->get('/results', 'ScannerController@scannerResult');
$router->get('/results/{id}', 'ScannerController@showByScannerId');
$router->get('/vulns/{id}', 'ScannerController@read');
