<?php

class productoPedido {
    public $idProductoPedido;
    public $idProducto;
    public $idPedido;
    public $tiempoPreparacion;
    public $cant;
    public $estado;
    // public static $estadosDisponibles = [
    //     "pendiente",            //solo mozos y socios pueden settear este estado
    //     "en preparación",       //solo empleados de cocina, barra y socios pueden settear este estado
    //     "listo para servir",    //solo empleados de cocina, barra y socios pueden settear este estado
    //     "servido"]; 

    public function crearProductoPedido() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO productoPedido (idProductoPedido, idProducto, idPedido, cant, tiempoPreparacion, estado) 
         VALUES (:idProductoPedido, :idProducto, :idPedido, :cant, :tiempoPreparacion, :estado)");
        $consulta->bindValue(':idProductoPedido', '',PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':cant', $this->cant, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Pedido::$estadosDisponibles[0], PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    //ver si lo de abajo persiste
    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productoPedido");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'productoPedido');
    }

    public static function obtenerProductoPedido($idProductoPedido) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                       FROM productoPedido 
                                                       WHERE idProductoPedido = :idProductoPedido");
        $consulta->bindValue(':idProductoPedido', $idProductoPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('productoPedido');
    }

    public static function obtenerCountDetalles($idPedido) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT count(idProductoPedido) 
                                                       FROM productoPedido 
                                                       WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch();
    }

    public static function obtenerIdProductoPedido($idProductoPedido) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProductoPedido
                                                       FROM productoPedido 
                                                       WHERE idProductoPedido = :idProductoPedido");
        $consulta->bindValue(':idProductoPedido', $idProductoPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('ProductoPedido');
    }

    public static function modificarProductoPedido($idProductoPedido, $estado) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productoPedido
                                                      SET estado = '{$estado}'
                                                      WHERE idProductoPedido = {$idProductoPedido}");
        $consulta->execute();
    }

    public static function detalleEstadoDelPedido($idPedido, $estado) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT count(estado) 
                                                      FROM productopedido 
                                                      WHERE idPedido = '{$idPedido}' 
                                                      and estado = '{$estado}'");
        $consulta->execute();
        return $consulta->fetch();
    }
    
    public static function obtenerDetallesPedido($idPedido) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProductoPedido 
                                                       FROM productopedido
                                                       WHERE idPedido = '{$idPedido}'");
        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_ASSOC);

        if ($consulta) {
            try {
                $consulta->execute();
                while ($row = $consulta->fetch()) {
                    $chars[] = $row;
                }
            }
            catch (PDOException $e) {
                var_dump($e);
            }
        }
        return $chars;
    }
}
