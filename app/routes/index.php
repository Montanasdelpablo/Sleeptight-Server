<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Require all routes

// Main route
$app->get('/', 'Controller:index');
$app->get('/hello/{name}', 'Controller:hello');

?>
