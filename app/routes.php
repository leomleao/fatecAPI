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
    $this->post('/login', _Controller::class.':login');
});

// $app->group('/alunos', function () {
//     $this->get   ('',             _Controller_oAuth2::class.':getAll');
//     $this->get   ('/{ra:[0-9]{3}[a-z][0-9]{2}}', _Controller::class.':get');
//     $this->post  ('',             _Controller::class.':add');
//     $this->put   ('/{id:[0-9]+}', _Controller::class.':update');
//     $this->delete('/{id:[0-9]+}', _Controller::class.':delete');
// });

$app->post('/grade', _Controller_oAuth2::class.':grade');

$app->post('/gradeSchedule', _Controller_oAuth2::class.':gradeSchedule');

$app->put('/changePassword', _Controller_oAuth2::class.':changePassword');

// Custom Controllers
//$app->group('/mycustom', function () {
//    $this->get   ('',             MyCustomController::class.':getAll');
//    $this->post
//    ...
//});
