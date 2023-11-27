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
        $usr->horaHecho = new DateTime();
        $ultimoId = $usr->crearPedido();
        
        foreach($productos as $producto){
            $prodActual = Producto::obtenerProductoNombre($producto['nombre']);
            $productoPedido = new productoPedido();
            $productoPedido->idProducto = $prodActual->idProducto;
            $productoPedido->idPedido = $ultimoId;
            $productoPedido->cant =  $producto['cantidad'];
            
            $seconds = strtotime("1970-01-01 $prodActual->tiempoPreparacion UTC");
            $multiply = $seconds * $producto['cantidad'];
            $time = gmdate("H:i:s",$multiply);
            $productoPedido->tiempoPreparacion = $time;
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

    public function TiempoDemora($request, $response, $args){
        $parametros = $request->getParsedBody();
        $idPedido = $args['idPedido'];
        $idMesa = $args['idMesa'];
        
        $consulta = Pedido::demora($idPedido, $idMesa);
        $payload = json_encode(array("el pedido estará aproximadamente a las " => $consulta['hora_de_pedido']));
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function traerPedidosEstado($request, $response, $args) {
        $params = $request->getQueryParams();
        $estado = $params['estado'];
        $lista = Pedido::obtenerTodos($estado);
        $payload = json_encode(array("lista de Pedidos listos en " . $estado => $lista), JSON_PRETTY_PRINT);

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
