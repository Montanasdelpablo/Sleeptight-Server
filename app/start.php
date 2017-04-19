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
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'https://yelbow.github.io')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

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



require 'models/Database.php';
require 'models/Senz2.php';

$container['db'] = function($container) {
  $dsn = $container->settings['db']['driver'] . ":host=" . $container->settings['db']['host'] . ";dbname=" . $container->settings['db']['database'];
  return new Database($dsn, $container->settings['db']['username'], $container->settings['db']['password']);
};

$container['senz2'] = function($container){
  return new Senz2();
};


// Run app
$app->run();

?>
