<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class PedidoStateMiddleware {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $permisoValido = false;
        $parametros = $request->getQueryParams();
        $bodyRequest = $request->getParsedBody();

        $permiso = $parametros['permiso'];
        $estado = $bodyRequest['estado'];

        switch ($permiso) {
            case "mozo":
                if($estado == Pedido::$estadosDisponibles[0] || $estado == Pedido::$estadosDisponibles[3]){
                    $permisoValido = true;
                }
                break;
            case "bartender":
            case "cocinero":
                if($estado == Pedido::$estadosDisponibles[1] || $estado == Pedido::$estadosDisponibles[2]){
                    $permisoValido = true;
                }
                break;
            case "socio":
                if($estado == Pedido::$estadosDisponibles[0] || $estado == Pedido::$estadosDisponibles[3]
                 ||$estado == Pedido::$estadosDisponibles[1] || $estado == Pedido::$estadosDisponibles[2]){
                    $permisoValido = true;
                 }
                break;
        }
        if ($permisoValido) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $mensaje = 'ERROR';
            if(!$permisoValido){
                $mensaje .= ' no tiene el permiso valido para ejecutar esta accion';
            }
            $payload = json_encode(array('mensaje' => $mensaje));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
