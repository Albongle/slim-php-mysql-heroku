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

require_once "./controllers/empleadosApi.php";
require_once "./controllers/productosApi.php";
require_once "./controllers/pedidosApi.php";
require_once "./controllers/mesasApi.php";

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);


// Routes
$app->group('/lacomanda', function (RouteCollectorProxy $group) {
    $group->post('/empleados', \EmpleadosApi::class . ':CargarUno');
    $group->get('/empleados', \EmpleadosApi::class . ':TraerTodos');
    $group->get('/empleados/{id}', \EmpleadosApi::class. ':TraerUno');
    $group->post('/productos', \ProductosApi::class . ':CargarUno');
    $group->get('/productos', \ProductosApi::class . ':TraerTodos');
    $group->get('/productos/{id}', \ProductosApi::class. ':TraerUno');
    $group->post('/pedidos', \PedidosApi::class . ':CargarUno');
    $group->get('/pedidos', \PedidosApi::class . ':TraerTodos');
    $group->get('/pedidos/{id}', \PedidosApi::class. ':TraerUno');
    $group->post('/mesas', \MesasApi::class . ':CargarUno');
    $group->get('/mesas', \MesasApi::class . ':TraerTodos');
});



$app->run();


?>