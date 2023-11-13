<?php

use Carbon\Factory;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './controllers/PersonalController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './models/productoPedido.php';
require_once './middlewares/LoggerMiddleware.php';
require_once './middlewares/StateChangeMiddleware.php';
require_once './utilities/Utilities.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->get('/', function ($request,  $response) {
  $response->getBody()->write("----------\n|Api HOME|\n ----------");
  return $response;
});

$app->group('/personal', function (RouteCollectorProxy $group) {

  $group->get('[/]', \PersonalController::class . ':TraerTodos');
  $group->get('/{DNI}', \PersonalController::class . ':TraerUno');
  $group->post('[/]', \PersonalController::class . ':CargarUno')
    ->add(new LoggerMiddleware);
  $group->put('/{DNI}', \PersonalController::class . ':ModificarUno')
    ->add(new LoggerMiddleware);
  $group->delete('/{DNI}', \PersonalController::class . ':BorrarUno')
    ->add(new LoggerMiddleware);
});

$app->group('/mesa', function (RouteCollectorProxy $group) {

  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{idMesa}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno')
    ->add(new LoggerMiddleware);
  $group->put('/{idMesa}', \MesaController::class . ':ModificarUno')
    ->add(new LoggerMiddleware);
  $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')
    ->add(new LoggerMiddleware);
});

$app->group('/pedido', function (RouteCollectorProxy $group) {

  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->put('/{idPedido}', \PedidoController::class . ':ModificarUno')
  ->add(new LoggerMiddleware());
  // ->add(new StateChangeMiddleware());
  $group->delete('/{idPedido}', \PedidoController::class . ':BorrarUno');
});

$app->group('/producto', function (RouteCollectorProxy $group) {

  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->put('/{idPedido}', \ProductoController::class . ':ModificarUno');
  $group->delete('/{idPedido}', \ProductoController::class . ':BorrarUno');
});

$app->run();
