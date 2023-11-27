<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MesaStateMiddleware {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $parametros = $request->getQueryParams();
        $bodyRequest = $request->getParsedBody();

        $data = AutentificadorJWT::ObtenerDataWithHeader($request->getHeaderLine('Authorization'));

        if(isset($bodyRequest['estado'])){
            $estado = $bodyRequest['estado'];
        }
        if ($data->rol == 'mozo' && $estado != Mesa::$estadosDisponibles[3] || $data->rol == 'socio') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $mensaje = 'ERROR';
            if($data->rol == 'mozo' && $estado == Mesa::$estadosDisponibles[3]) {
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
