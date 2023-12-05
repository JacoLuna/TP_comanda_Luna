<?php
require_once './models/Factura.php';
require_once './interfaces/IApiUsable.php';

class FacturaController extends Factura implements IApiUsable {
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $propina = $parametros['propina'];
        $precio = Pedido::precio($idPedido);
        $usr = new Factura();
        $usr->idPedido = $idPedido;
        $usr->propina = $propina;
        $usr->precio = $precio;
        $usr->crearFactura();

        $payload = json_encode(array("mensaje" => "Factura creada con exito"));
        
        $pedido = Pedido::obtenerPedido($idPedido);
        Mesa::modificarMesa($pedido->idMesa, Mesa::$estadosDisponibles[2]);
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) {
        $usr = $args['idFactura'];
        $Factura = Factura::obtenerUnaFactura($usr);
        $payload = json_encode($Factura);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args) {
        $lista = Factura::obtenerTodos();
        $payload = json_encode(array("listaFactura" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
    }
    public function BorrarUno($request, $response, $args) {
    }
}
