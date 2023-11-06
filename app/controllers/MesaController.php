<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $idPersonal = $parametros['idPersonal'];
        $cantComensales = $parametros['cantComensales'];
        $codigo = $parametros['codigo'];

        $usr = new Mesa();
        $usr->idPersonal = $idPersonal;
        $usr->cantComensales = $cantComensales;
        $usr->codigo = $codigo;
        $usr->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args) {
        $usr = $args['codigo'];
        $mesa = Mesa::obtenerMesa($usr);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function TraerID($args) {
        $usr = $args['codigo'];
        $codigo = Mesa::obtenerIdMesa($usr);
        return $codigo;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $codigo = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $idPersonal = $parametros['idPersonal'];
        $cantComensales = $parametros['cantComensales'];
        $codigo = $parametros['codigo'];

        Mesa::modificarMesa($codigo->codigo, $idPersonal, $cantComensales, $codigo);

        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $codigo = $this->TraerID($args);
        Mesa::borrarMesa($codigo->codigo);

        $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
