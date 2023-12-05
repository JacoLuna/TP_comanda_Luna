<?php
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';

class ProductoPedidoController extends ProductoPedido implements IApiUsable {

    public function CargarUno($request, $response, $args) {
    }
    public function TraerUno($request, $response, $args) {
        $idProductoPedido = $args['idProductoPedido'];
        $productoPedido = productoPedido::obtenerProductoPedido($idProductoPedido);

        $payload = json_encode($productoPedido);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args) {
    }
    public function ModificarUno($request, $response, $args) {
        $idProductoPedido = $args['idProductoPedido'];
        $parametros = $request->getParsedBody();
        $estado = $parametros['estado'];

        $productoPedido = productoPedido::obtenerProductoPedido($idProductoPedido);
        $mensaje = array("mensaje" => "item modificado con exito");

        if(isset($parametros['tiempo'])){
            $tiempo = explode(":", $parametros['tiempo']);
            $tiempo = date('H:i:s', strtotime("00/00/0000" . $tiempo[0] . " hours " . $tiempo[1] . " minutes " . $tiempo[2] . " seconds"));
            ProductoPedido::modificarProductoPedido($idProductoPedido, $estado, $tiempo);
        }else{
            ProductoPedido::modificarProductoPedido($idProductoPedido, $estado);
        }
        $pedido = Pedido::obtenerPedido($productoPedido->idPedido);
        
        if($pedido->estado == Pedido::$estadosDisponibles[0]){
            Pedido::modificarPedido($pedido->idPedido, Pedido::$estadosDisponibles[1]);
        }
        if($estado == Pedido::$estadosDisponibles[2]){
            $consultaCountEstado = ProductoPedido::detalleEstadoDelPedido($pedido->idPedido, $estado);
            $consultaCountDetalles = ProductoPedido::obtenerCountDetalles($pedido->idPedido);
            
            $countEstado = $consultaCountEstado[0];
            $countDetalles = $consultaCountDetalles[0];

            if($countEstado == $countDetalles){
                Pedido::modificarPedido($pedido->idPedido, Pedido::$estadosDisponibles[2]);
                $mensaje = array_merge($mensaje, array("productos" => "todos los productos del pedido están hechos"));
            }else{
                $mensaje = array_merge($mensaje, 
                array("productos" => "quedan " . ($countDetalles-$countEstado) . " productos del pedido están hechos"));
            }
        }
        
        $payload = json_encode($mensaje);
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args) {
    }
}
