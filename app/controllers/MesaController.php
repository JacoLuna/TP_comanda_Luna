<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $usr = new Mesa();
        $usr->idPersonal = 1;
        $usr->cantComensales = 0;
        $usr->rota = false;
        $usr->estado = Mesa::$estadosDisponibles[3];
        $usr->primera = Mesa::esPrimeraTupla();
        $usr->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args) {
        $usr = $args['idMesa'];
        $mesa = Mesa::obtenerUnaMesa($usr);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function TraerID($args) {
        $usr = $args['idMesa'];
        $idMesa = Mesa::obtenerIdMesa($usr);
        return $idMesa;
    }
    private function TraerUnaMesa($args) {
        $usr = $args['idMesa'];
        $mesa = Mesa::obtenerUnaMesa($usr);
        return $mesa;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $idMesa = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $estado = "";
        if(isset($parametros["estado"])){
            $estado = $parametros['estado'];
            Mesa::modificarMesa($idMesa->idMesa, $estado);
        }else{
            $idPersonal = $parametros['idPersonal'];
            $cantComensales = $parametros['cantComensales'];
            Mesa::modificarMesa($idMesa->idMesa, $estado, $idPersonal, $cantComensales);
        }
        
        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function cambiarEstado($request, $response, $args) {
        $idMesa = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $idPersonal = $parametros['idPersonal'];
        $cantComensales = $parametros['cantComensales'];

        Mesa::modificarMesa($idMesa->idMesa, $idPersonal, $cantComensales, $idMesa);

        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $idMesa = $this->TraerID($args);
        Mesa::borrarMesa($idMesa->idMesa);

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
