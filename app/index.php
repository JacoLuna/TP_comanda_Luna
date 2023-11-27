<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './controllers/PersonalController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/ProductoPedidoController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/FacturaController.php';
require_once './models/productoPedido.php';
require_once './middlewares/SocioMiddleware.php';
require_once './middlewares/PedidoStateMiddleware.php';
require_once './middlewares/MesaStateMiddleware.php';
require_once './middlewares/AuthMiddleware.php';
require_once './utilities/AutentificadorJWT.php';
require_once './utilities/Utilities.php';

define('TIMEZONE', 'America/Argentina/Buenos_Aires');
date_default_timezone_set(TIMEZONE);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->get('/', function ($request,  $response) {
  $response->getBody()->write("----------\n|Api HOME|\n ----------");
  return $response;
});

$app->group('/auth', function (RouteCollectorProxy $group) {
  $group->post('/logIn', \PersonalController::class . ':logIn');
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
})->add(new AuthMiddleware);

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
})->add(new AuthMiddleware);

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/estado', \PedidoController::class . ':traerPedidosEstado');
  $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
  $group->get('/{idPedido}/{idMesa}', \PedidoController::class . ':TiempoDemora');

  $group->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(new SocioMiddleware);

  $group->put('/{idPedido}', \PedidoController::class . ':ModificarUno');
  $group->put('/{idPedido}/mozo', \PedidoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());

  $group->delete('/{idPedido}', \PedidoController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
  // });
})->add(new AuthMiddleware);

$app->group('/detalle', function (RouteCollectorProxy $group) {
  $group->put('/{idProductoPedido}/cocinas_barras', \ProductoPedidoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());
})->add(new AuthMiddleware);

$app->group('/producto', function (RouteCollectorProxy $group) {

  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \ProductoController::class . ':TraerUno');

  $group->get('/archivo/descarga', \ProductoController::class . ':descargarArchivoCsv')
    ->add(new SocioMiddleware);

  $group->post('[/]', \ProductoController::class . ':CargarUno')
    ->add(new SocioMiddleware);

  $group->post('/archivo', \ProductoController::class . ':cargarArchivoCsv')
    ->add(new SocioMiddleware);

  $group->put('/{idPedido}', \ProductoController::class . ':ModificarUno')
    ->add(new PedidoStateMiddleware());
  $group->delete('/{idPedido}', \ProductoController::class . ':BorrarUno')
    ->add(new SocioMiddleware);
})->add(new AuthMiddleware);


$app->group('/encuesta', function (RouteCollectorProxy $group) {

  $group->get('[/]', \EncuestaController::class . ':TraerTodos');
  $group->get('/{idEncuesta}', \EncuestaController::class . ':TraerUno');

  $group->post('[/]', \EncuestaController::class . ':CargarUno');
});

$app->group('/factura', function (RouteCollectorProxy $group) {

  $group->get('[/]', \FacturaController::class . ':TraerTodos');
  $group->get('/{idFactura}', \FacturaController::class . ':TraerUno');

  $group->post('[/]', \FacturaController::class . ':CargarUno');
});
$app->run();
