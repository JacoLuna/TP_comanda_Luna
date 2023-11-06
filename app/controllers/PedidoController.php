<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];

        $usr = new Pedido();
        $usr->idMesa = $idMesa;
        $usr->estado = $estado;
        $usr->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        // Buscamos pedido por id
        $usr = $args['idPedido'];
        $pedido = Pedido::obtenerPedido($usr);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function TraerID($args) {
        $usr = $args['idPedido'];
        $id = Pedido::obtenerIdPedido($usr);
        return $id;
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

//CONTROLAR
    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idPedido = $parametros['idPedido'];
        $estado = $parametros['estado'];

        Pedido::modificarPedido($idPedido, $estado);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

//CONTROLAR
    public function BorrarUno($request, $response, $args) {
        $id = $this->TraerID($args);
        Pedido::terminarPedido($id->id);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
