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
$app->group('/lacomanda', function (RouteCollectorProxy $group) {
    $group->post('/empleados', \EmpleadoApi::class . ':CargarUno');
    $group->get('/empleados', \EmpleadoApi::class . ':TraerTodos');
    $group->get('/empleados/{id}', \EmpleadoApi::class. ':TraerUno');
    $group->post('/productos', \ProductosApi::class . ':CargarUno');
    $group->get('/productos', \ProductosApi::class . ':TraerTodos');
});



$app->run();


?>