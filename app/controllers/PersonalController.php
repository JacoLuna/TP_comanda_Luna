<?php
require_once './models/Personal.php';
require_once './interfaces/IApiUsable.php';

class PersonalController extends Personal implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $contrasenia = $parametros['contrasenia'];
        $DNI = $parametros['DNI'];
        $rol = $parametros['rol'];
        $fechaIngreso = $parametros['fechaIngreso'];
        
        $usr = new Personal();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->contrasenia = $contrasenia;
        $usr->fechaIngreso = $fechaIngreso;
        $usr->DNI = $DNI;
        $usr->rol = $rol;
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
    // private function TraerID($args) {
    //     $usr = $args['DNI'];
    //     $id = Personal::obtenerIdPersonal($usr);
    //     return $id;
    // }    
    private function TraerUnEmpleado($args) {
        $usr = $args['DNI'];
        $empleado = Personal::obtenerPersonal($usr);
        return $empleado;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Personal::obtenerTodos();
        $payload = json_encode(array("listaPersonal" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $empleado = $this->TraerUnEmpleado($args);
        $parametros = $request->getParsedBody();

        $nombre = ($parametros['nombre'] != "")?$parametros['nombre'] : $empleado->nombre ;
        $apellido = ($parametros['apellido'] != "")?$parametros['apellido'] : $empleado->apellido ;
        $DNI = ($parametros['DNI'] != "")?$parametros['DNI'] : $empleado->DNI ;
        $rol = ($parametros['rol'] != "")?$parametros['rol'] : $empleado->rol ;
        $fechaIngreso = ($parametros['fechaIngreso'] != "")?$parametros['fechaIngreso'] : $empleado->fechaIngreso ;

        Personal::modificarPersonal($empleado->idPersonal, $nombre, $apellido, $DNI, $rol, $fechaIngreso);

        $payload = json_encode(array("mensaje" => "Personal modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $empleado = $this->TraerUnEmpleado($args);
        Personal::borrarPersonal($empleado->idPersonal);

        $payload = json_encode(array("mensaje" => "Personal borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
