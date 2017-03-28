<?php

//Start Session
session_start();

// Require autoload
require 'vendor/autoload.php';

// Configuration for Slim app
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
          'driver' => 'mysql',
          'host' => 'localhost',
          'database' => 'sleeptight',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          'collation' => 'utf8_unicode_ci'
          ]
    ],

];

// Make new Slim app
$app = new \Slim\App($configuration);

// Grab conainer from app
$container = $app->getContainer();

// Require routes and views

//require 'views.php';
require 'routes.php';

// Make capsule from Eloquent for Database
//$capsule = new \Illuminate\Database\Capsule\Manager;
//$capsule->addConnection($container['settings']['db']);
//$capsule->setAsGlobal();
//$capsule->bootEloquent();

//$container['db'] = function ($container) use ($capsule) {
//  return $capsule;
// };

// Controllers
require 'controllers/Controller.php';

$container['Controller'] = function ($container) {
  return new App\Controllers\Controller($container);
};




// Run app
$app->run();

?>
