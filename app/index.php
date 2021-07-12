<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Exception\NotFoundException;
use Illuminate\Database\Capsule\Manager as Capsule;


require_once "../vendor/autoload.php";
require_once "./app/middlewares/middleware.php";
require_once "./app/controllers/UsuarioController.php";
require_once "./app/controllers/ProductoController.php";
require_once "./app/controllers/MesaController.php";
require_once "./app/controllers/PedidoController.php";

date_default_timezone_set('America/Argentina/Buenos_Aires');


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();



// Instantiate App
$app = AppFactory::create();
$app->setBasePath("/LaComanda"); 
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
// Add error middleware
$app->addErrorMiddleware(true, true, true);


// Eloquent
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['MYSQL_HOST'],
    'database'  => $_ENV['MYSQL_DB'],
    'username'  => $_ENV['MYSQL_USER'],
    'password'  => $_ENV['MYSQL_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


// login
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':Login');


});

// usuarios
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(new Middleware("Admin"));
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/buscar/', \UsuarioController::class. ':TraerUno')->add(new Middleware("Admin"));
  $group->put('[/]', \UsuarioController::class. ':ModificarUno')->add(new Middleware("Admin"));
  $group->delete('[/]', \UsuarioController::class. ':BorrarUno')->add(new Middleware("Admin"));

});

//productos
$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new Middleware("Socio"));
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/buscar/', \ProductoController::class. ':TraerUno');
  $group->put('[/]', \ProductoController::class. ':ModificarUno')->add(new Middleware("Socio"));

});

//mesas
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->post('[/]', \MesaController::class . ':CargarUno'); 
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->put('[/]', \MesaController::class. ':ModificarUno')->add(new Middleware("Mozo"));
  $group->put('/cerrar', \MesaController::class. ':CerrarMesa')->add(new Middleware("Socio"));
});
//pedidos
$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new Middleware("Mozo")); 
    $group->get('/listar', \PedidoController::class . ':TraerTodos')->add(new Middleware("Socio"));
    $group->get('/usuario/buscar/', \PedidoController::class. ':TraerUno');
    $group->get('/empleados/buscar/', \PedidoController::class. ':TraerUno')->add(new Middleware("Empleado"));
    $group->put('[/]', \PedidoController::class . ':ModificarUno')->add(new Middleware("Empleado"));
    $group->delete('[/]', \PedidoController::class . ':BorrarUno')->add(new Middleware("Empleado"));
    $group->get('/buscarbajas/', \PedidoController::class . ':BuscarBaja'); 

});

$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("La Comanda Alejandro Bongioanni");
  return $response;

});

$app->run();


?>