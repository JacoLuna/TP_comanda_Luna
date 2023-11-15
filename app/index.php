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
require_once './middlewares/SocioMiddleware.php';
require_once './middlewares/PedidoStateMiddleware.php';
require_once './middlewares/MesaStateMiddleware.php';
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
    ->add(new SocioMiddleware);
  $group->put('/{DNI}', \PersonalController::class . ':ModificarUno')
    ->add(new SocioMiddleware);
  $group->delete('/{DNI}', \PersonalController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
});

$app->group('/mesa', function (RouteCollectorProxy $group) {

  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{idMesa}', \MesaController::class . ':TraerUno');

  $group->post('[/]', \MesaController::class . ':CargarUno')
    ->add(new SocioMiddleware);

  $group->put('/{idMesa}', \MesaController::class . ':ModificarUno')
    ->add(new MesaStateMiddleware);
  $group->put('/{idMesa}/cerrarMesa', \MesaController::class . ':ModificarUno')
    ->add(new MesaStateMiddleware);
  $group->put('/{idMesa}/cambiarEstado', \MesaController::class . ':ModificarUno')
    ->add(new MesaStateMiddleware);

  $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
});

$app->group('/pedido', function (RouteCollectorProxy $group) {

  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(new SocioMiddleware);

  $group->put('/{idPedido}', \PedidoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());
  $group->put('/{idPedido}/mozo', \PedidoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());
  $group->put('/{idPedido}/cocina', \PedidoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());

  $group->delete('/{idPedido}', \PedidoController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
});

$app->group('/producto', function (RouteCollectorProxy $group) {

  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno')
    ->add(new SocioMiddleware);
  $group->put('/{idPedido}', \ProductoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());
  $group->delete('/{idPedido}', \ProductoController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
});

$app->run();
