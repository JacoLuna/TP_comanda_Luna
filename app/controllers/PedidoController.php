<?php

use Psr7Middlewares\Middleware\Payload;

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable {

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $nombreCliente = $parametros['nombreCliente'];
        $productos = $parametros['productos'];
        $usr = new Pedido();
        $usr->idMesa = $idMesa;
        $usr->nombreCliente = $nombreCliente;
        $usr->horaHecho = new DateTime();
        $ultimoId = $usr->crearPedido();
        
        foreach($productos as $producto){
            $prodActual = Producto::obtenerProductoNombre($producto['nombre']);
            $productoPedido = new productoPedido();
            $productoPedido->idProducto = $prodActual->idProducto;
            $productoPedido->idPedido = $ultimoId;
            $productoPedido->cant =  $producto['cantidad'];
            $productoPedido->tiempoPreparacion = 0;
            $productoPedido->crearProductoPedido();
        }

        if (!$_FILES["imagen"]["error"]) {
            $partesRuta = explode(".", $_FILES["imagen"]["name"]);
            $extension = end($partesRuta);
            $destino = "img/" . $ultimoId . "-" . $usr->idMesa . '.' . $extension;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino);
        }

        $data = AutentificadorJWT::ObtenerDataWithHeader($request->getHeaderLine('Authorization'));
        
        $cantComensales = $parametros['cantComensales'];
        Mesa::modificarMesa($idMesa, Mesa::$estadosDisponibles[0], $data->idPersonal, $cantComensales);

        $payload = json_encode(array("mensaje" => "Pedido creado con exito, su codigo es " . $ultimoId));
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args) {
        $usr = $args['idPedido'];
        $data = AutentificadorJWT::ObtenerDataWithHeader($request->getHeaderLine('Authorization'));

        $pedidoProductos = Pedido::obtenerProductosDelPedido($usr, $data->rol);
        
        $payload = json_encode($pedidoProductos);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idPedido = $args['idPedido'];
        $estado = "";
        
        $payload = json_encode(array("mensaje" => "Hubo un error al modificar el pedido"));

        if(isset($parametros['estado'])){
            $estado = $parametros['estado'];

            foreach(Pedido::$estadosDisponibles as $estadoDisp){
                if($estadoDisp ==  $estado){
                    Pedido::modificarPedido($idPedido, $estado);

                    if($estado == Pedido::$estadosDisponibles[3]){
                        $consultaCountDetalles = ProductoPedido::obtenerCountDetalles($idPedido);
                        $countDetalles = $consultaCountDetalles[0];
                        $productosID = productoPedido::obtenerDetallesPedido($idPedido);

                        for($i = 0 ; $i < $countDetalles ; $i++){
                            productoPedido::modificarProductoPedido($productosID[$i]["idProductoPedido"],$estado);
                        }

                        $pedido = Pedido::obtenerPedido($idPedido);
                        Mesa::modificarMesa($pedido->idMesa, Mesa::$estadosDisponibles[1]);
                    }

                    $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
                    break;
                }
            }
        }else{
            $nombreCliente = $parametros['nombreCliente'];
            Pedido::modificarPedido($idPedido, $estado , $nombreCliente);
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args) {
    }
    private function TraerID($args) {
        $usr = $args['idPedido'];
        $id = Pedido::obtenerIdPedido($usr);
        return $id;
    }
    public function TiempoDemora($request, $response, $args){
        $parametros = $request->getParsedBody();
        $idPedido = $args['idPedido'];
        $idMesa = $args['idMesa'];
        
        $payload = json_encode(array("el pedido estará aproximadamente a las " => $this->calcularHora($idPedido, $idMesa)));
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function traerPedidosEstado($request, $response, $args) {
        $params = $request->getQueryParams();
        $estado = $params['estado'];
        $lista = Pedido::obtenerTodos($estado);
        $payload = json_encode(array("lista de Pedidos " . $estado => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodosConTiempo($request, $response, $args){
        $pedidos = Pedido::obtenerTodos();
        $payload = "";
        $data = AutentificadorJWT::ObtenerDataWithHeader($request->getHeaderLine('Authorization'));

        for($i = 0 ; $i < count($pedidos) ; $i++){
            $payload .= json_encode( array("pedido" => $pedidos[$i]->idPedido,
                                           "hora de entrega" => $this->calcularHora($pedidos[$i]->idPedido, $pedidos[$i]->idMesa)));
        } 

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function calcularHora($idPedido, $idMesa){
        $consulta = Pedido::demora($idPedido, $idMesa);
        $horas = substr($consulta['hora_de_pedido'], 0, 2);
        $minutos = substr($consulta['hora_de_pedido'], 2, 2);
        $segundos = substr($consulta['hora_de_pedido'], 4, 2);
        $tiempo = date('H:i:s', strtotime("00/00/0000" . $horas . " hours " . $minutos . " minutes " . $segundos . " seconds"));
        return $tiempo;
    }
}
