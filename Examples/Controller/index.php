<?php
include_once './../../src/class/autoload.php';

$controller = new Controller('controllers');
$controller->useController('RequestLogin'); // RequestLogin is the controller in controllers/request/loginController.php
