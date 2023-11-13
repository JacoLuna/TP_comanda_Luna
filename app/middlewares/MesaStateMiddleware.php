<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MesaStateMiddleware {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $parametros = $request->getQueryParams();
        $bodyRequest = $request->getParsedBody();

        $permiso = $parametros['permiso'];
        $estado = $bodyRequest['estado'];

        if ($permiso == 'mozo' && $estado != Mesa::$estadosDisponibles[3] || $permiso == 'socio') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $mensaje = 'ERROR';
            if($permiso == 'mozo' && $estado == Mesa::$estadosDisponibles[3]) {
                $mensaje .= ' solo los socios pueden cerrar una mesa';
            }else{
                $mensaje .= ' no tiene el permiso valido para ejecutar esta accion';
            }
            $payload = json_encode(array('mensaje' => $mensaje));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
