<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Get server status
$app->get('/', 'Controller:index');

// POST - Register
$app->post('/api/register', 'Controller:register');

//POST - Authentication
$app->post('/api/auth', 'Controller:auth');

// Alle gebruikers
$app->get('/api/gebruikers', 'Controller:gebruikers');

// Gebruiker volgens id
$app->get('/api/gebruiker/{uid}', 'Controller:gebruiker');

// Get client data volgends id
$app->get('/api/client/{id}', 'Controller:client');

// Get sensor data volgens id
$app->get('/api/sensor/{id}', 'Controller:sensor');


?>
