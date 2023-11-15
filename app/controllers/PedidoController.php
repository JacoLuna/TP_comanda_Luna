<?php
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
        $usr->crearPedido();

        $idPedido = Pedido::obtenerUltimoId();

        foreach($productos as $producto){
            $prodActual = Producto::obtenerProductoNombre($producto['nombre']);
            $productoPedido = new productoPedido();
            $productoPedido->idProducto = $prodActual->idProducto;
            $productoPedido->idPedido = $idPedido->ultimoId;
            $productoPedido->cant =  $producto['cantidad'];
            $productoPedido->tiempoPreparacion =  $prodActual->tiempoPreparacion;
            $productoPedido->crearProductoPedido();
        }

        // if (!$_FILES["imagen"]["error"]) {
        //     $partesRuta = explode(".", $_FILES["imagen"]["name"]);
        //     $extension = end($partesRuta);
        //     $destino = "img/" . $idPedido->ultimoId . "-" . $usr->idMesa . '.' . $extension;
        //     move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino);
        // }

        $payload = json_encode(array("mensaje" => "Pedido creado con exito, su codigo es " . $idPedido->ultimoId));
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        // Buscamos pedido por id
        $usr = $args['idPedido'];
        
        $pedidoPrductos = Pedido::obtenerProdcutosDelPedido($usr);
        
        $payload = json_encode($pedidoPrductos);

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

//CONTROLAR
    public function BorrarUno($request, $response, $args) {
        // $id = $this->TraerID($args);
        // Pedido::BorrarUno($id->id);

        // $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        // $response->getBody()->write($payload);
        // return $response
        //     ->withHeader('Content-Type', 'application/json');
    }
}
