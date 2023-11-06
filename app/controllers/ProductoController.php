<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $zona = $parametros['zona'];

        $usr = new Producto();
        $usr->nombre = $nombre;
        $usr->tiempoPreparacion = $tiempoPreparacion;
        $usr->zona = $zona;
        $usr->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
//CONTROLAR
    public function TraerUno($request, $response, $args) {
        $usr = $args['idProducto'];
        $producto = Producto::obtenerProducto($usr);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
//CONTROLAR
    private function TraerID($args) {
        $usr = $args['idProducto'];
        $codigo = Producto::obtenerIdProducto($usr);
        return $codigo;
    }
    
    public function TraerTodos($request, $response, $args) {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
//CONTROLAR
    public function ModificarUno($request, $response, $args) {
        $idPersonal = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $zona = $parametros['zona'];

        Producto::modificarProducto($idPersonal->idPersonal, $nombre, $tiempoPreparacion, $zona);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
//CONTROLAR
    public function BorrarUno($request, $response, $args) {
        $idPersonal = $this->TraerID($args);
        Producto::borrarProducto($idPersonal->idPersonal);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
