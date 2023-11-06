<?php
require_once './models/Personal.php';
require_once './interfaces/IApiUsable.php';

class PersonalController extends Personal implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $fechaIngreso = $parametros['fechaIngreso'];

        $usr = new Personal();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->fechaIngreso = $fechaIngreso;
        $usr->crearPersonal();

        $payload = json_encode(array("mensaje" => "Personal creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        // Buscamos personal por DNI
        $usr = $args['DNI'];
        $personal = Personal::obtenerPersonal($usr);
        $payload = json_encode($personal);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function TraerID($args) {
        $usr = $args['DNI'];
        $id = Personal::obtenerIdPersonal($usr);
        return $id;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Personal::obtenerTodos();
        $payload = json_encode(array("listaPersonal" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $id = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $DNI = $parametros['DNI'];
        $fechaIngreso = $parametros['fechaIngreso'];

        Personal::modificarPersonal($id->id, $nombre, $apellido, $DNI, $fechaIngreso);

        $payload = json_encode(array("mensaje" => "Personal modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $id = $this->TraerID($args);
        Personal::borrarPersonal($id->id);

        $payload = json_encode(array("mensaje" => "Personal borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
