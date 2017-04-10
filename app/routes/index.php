<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Require all routes

// Main route

// Normal JSON (GET)

// Register
$app->post('/api/register', 'Controller:register');

//Auth
$app->post('/api/auth', 'Controller:auth');

// Alle gebruikers
$app->get('/api/gebruikers', 'Controller:gebruikers');
// Gebruiker volgens id
$app->get('/api/gebruiker/{uid}', 'Controller:gebruiker');

?>
