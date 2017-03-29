<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Require all routes

// Main route

// Normal JSON (GET)
$app->get('/', 'Controller:index');

// 1 Parameter JSON (GET)
$app->get('/hello/{name}', 'Controller:hello');


// 2 Parameter JSON (GET)
$app->get('/{intent}/{uid}', 'Controller:action');

// Protected JSON (GET)
$app->get('/{key}', 'Controller:protect');

?>
