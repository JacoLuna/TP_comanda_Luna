<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

    class StateChangeMiddleware{
        
        public function __invoke(Request $request, RequestHandler $handler): Response{
            // $parametros = $request->getParsedBody();

            // $estado = $parametros['estado'];

            // return $response->withHeader('Content-Type', 'application/json');
        }
    }
