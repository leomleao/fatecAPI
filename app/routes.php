<?php

use App\Controllers\_ApiController;

use App\Controllers\_Controller;
use App\Controllers\_Controller_oAuth2;
use App\Controllers\_oAuth2TokenController;

// Custom Controllers
//use App\Controllers\MyCustomController;


// Routes

$app->get('/', _ApiController::class.':showHello')
    ->setName('hellopage');

// oAuth2
$app->group('/oauth', function () {
    $this->post('/token', _oAuth2TokenController::class.':token');
});

// Books controller
$app->group('/books', function () {
    $this->get   ('',             _Controller_oAuth2::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _Controller_oAuth2::class.':get');
    $this->post  ('',             _Controller_oAuth2::class.':add');
    $this->put   ('/{id:[0-9]+}', _Controller_oAuth2::class.':update');
    $this->delete('/{id:[0-9]+}', _Controller_oAuth2::class.':delete');
    
//})->add(function ($request, $response, $next) {
//	$this->settings['localtable'] = "categories";
//    $response = $next($request, $response);
//    return $response;
});

$app->group('/alunos', function () {
    $this->get   ('',             _Controller::class.':getAll');
    $this->get   ('/{id:[0-9]+}', _Controller::class.':get');
    $this->post  ('',             _Controller::class.':add');
    $this->put   ('/{id:[0-9]+}', _Controller::class.':update');
    $this->delete('/{id:[0-9]+}', _Controller::class.':delete');
});

// Custom Controllers
//$app->group('/mycustom', function () {
//    $this->get   ('',             MyCustomController::class.':getAll');
//    $this->post
//    ...
//});
