<?php
// Will include all your libraies
include_once __DIR__ . '/autoload.php';


$test = 'test';
// New controller
$controller = new Controller('controllers');
$controller->useController('RequestLogin'); // RequestLogin is the controller in controllers/request/loginController.php

