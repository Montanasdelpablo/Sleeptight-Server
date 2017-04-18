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

// Get sensor data volgens id en timestamp
$app->get('/api/sensor/{id}', 'Controller:sensor');

// Get sensor data 24 uur geleden tot nu
$app->get('/api/sensor/{id}/lastday', 'Controller:lastday');

// Get sensor data van afgelopen week
$app->get('/api/sensor/{id}/lastweek', 'Controller:lastweek');

// Get sensor data volgens id en timestamp
$app->get('/api/sensor/{id}/{from}/{to}', 'Controller:sensorbytime');


?>
