<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once "./db/AccesoDatos.php";
// require_once './middlewares/Logger.php';

require_once "./controllers/empleadoApi.php";

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);


// Routes
$app->group('/empleados', function (RouteCollectorProxy $group) {

  $group->post('/', \EmpleadoApi::class . ':CargarUno');
  $group->get('/', \EmpleadoApi::class . ':TraerTodos');

});

$app->run();




?>