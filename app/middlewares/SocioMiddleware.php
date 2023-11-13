<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

    class SocioMiddleware{
        
        public function __invoke(Request $request, RequestHandler $handler): Response{
            $parametros = $request->getQueryParams();
            $permiso = $parametros['permiso'];
            if ($permiso == 'socio') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Solo los socios pueden ejecutar esta accion'));
                $response->getBody()->write($payload);
            }
    
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
