<?php

session_start();
include_once './../../src/class/autoload.php';

$route = new Route();
$route->fromFile(
    __DIR__ . '/routes.json',
    __DIR__ . '/controllers',
    __DIR__ . '/templates'
);
