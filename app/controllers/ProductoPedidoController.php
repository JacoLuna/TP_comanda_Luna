<?php
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';

class ProductoPedidoController extends ProductoPedido implements IApiUsable {

    public function CargarUno($request, $response, $args) {
    }
    public function TraerUno($request, $response, $args) {
    }
    public function TraerTodos($request, $response, $args) {
    }
    public function ModificarUno($request, $response, $args) {
        $idProductoPedido = $args['idProductoPedido'];
        // $idProductoPedido = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $estado = $parametros['estado'];

        ProductoPedido::modificarProductoPedido($idProductoPedido, $estado);
        $payload = json_encode(array("mensaje" => "item modificado con exito"));
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args) {
    }
}
