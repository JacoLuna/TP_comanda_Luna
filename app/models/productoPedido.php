<?php

class productoPedido {
    public $idProductoPedido;
    public $idProducto;
    public $idPedido;
    public $tiempoPreparacion;

    public function crearProductoPedido() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productoPedido (idProductoPedido, idProducto, idPedido, tiempoPreparacion) 
        VALUES (:idProductoPedido, :idProducto, :idPedido, :tiempoPreparacion)");
        $consulta->bindValue(':idProductoPedido', '',PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProductoPedido, idMesa, estado 
                                                       FROM productoPedido 
                                                       WHERE idProductoPedido = :idProductoPedido");
        $consulta->bindValue(':idProductoPedido', $idProductoPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('productoPedido');
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
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ProductoPedido
                                                      SET estado = '{$estado}',
                                                      WHERE idProductoPedido = {$idProductoPedido}");
        $consulta->execute();
    }

    // public static function terminarProductoPedido($idProductoPedido) {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta(
    //         "UPDATE ProductoPedido 
    //          SET estado = 'cerrado' 
    //          WHERE idProductoPedido = {$idProductoPedido}");
    //     $consulta->execute();
    // }
}
