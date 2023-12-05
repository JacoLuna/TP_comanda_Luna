<?php
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $mesa = $parametros['mesa'];
        $restaurante = $parametros['restaurante'];
        $mozo = $parametros['mozo'];
        $cocinero = $parametros['cocinero'];
        $encuesta = $parametros['encuesta'];

        $usr = new Encuesta();
        $usr->idPedido = $idPedido;
        $usr->mesa = $mesa;
        $usr->restaurante = $restaurante;
        $usr->mozo = $mozo;
        $usr->cocinero = $cocinero;
        $usr->encuesta = $encuesta;
        $usr->crearEncuesta();

        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        $usr = $args['idEncuesta'];
        $Encuesta = Encuesta::obtenerUnaEncuesta($usr);
        $payload = json_encode($Encuesta);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function TraerID($args) {
        $usr = $args['idEncuesta'];
        $idEncuesta = Encuesta::obtenerIdEncuesta($usr);
        return $idEncuesta;
    }
    private function TraerUnaEncuesta($args) {
        $usr = $args['idEncuesta'];
        $Encuesta = Encuesta::obtenerUnaEncuesta($usr);
        return $Encuesta;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $idEncuesta = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $estado = "";
        if(isset($parametros["estado"])){
            $estado = $parametros['estado'];
            Encuesta::modificarEncuesta($idEncuesta->idEncuesta, $estado);
        }else{
            $idPersonal = $parametros['idPersonal'];
            $cantComensales = $parametros['cantComensales'];
            Encuesta::modificarEncuesta($idEncuesta->idEncuesta, $estado, $idPersonal, $cantComensales);
        }
        
        $payload = json_encode(array("mensaje" => "Encuesta modificado con exito"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function cambiarEstado($request, $response, $args) {
        $idEncuesta = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $idPersonal = $parametros['idPersonal'];
        $cantComensales = $parametros['cantComensales'];

        Encuesta::modificarEncuesta($idEncuesta->idEncuesta, $idPersonal, $cantComensales, $idEncuesta);

        $payload = json_encode(array("mensaje" => "Encuesta modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $idEncuesta = $this->TraerID($args);
        Encuesta::borrarEncuesta($idEncuesta->idEncuesta);

        $payload = json_encode(array("mensaje" => "Encuesta borrada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresComentarios($request, $response, $args){
        $comentarios = Encuesta::mejoresComentarios();

        $payload = json_encode(array("mejores comentarios" => $comentarios));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
