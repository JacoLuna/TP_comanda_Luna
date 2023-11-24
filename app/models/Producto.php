<?php
class Producto {
    public $idProducto = -1;
    public $nombre;
    public $tiempoPreparacion;
    public $zona;
    public $baja = false;

    public function crearProducto() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (idProducto, nombre, tiempoPreparacion, zona, baja) 
        VALUES (:idProducto, :nombre, :tiempoPreparacion, :zona, :baja)");
        if($this->idProducto == -1){
            $consulta->bindValue(':idProducto', '', PDO::PARAM_INT);
        }else{
            $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        }
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':zona', $this->zona, PDO::PARAM_STR);
        $consulta->bindValue(':baja', $this->baja, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($idProducto) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProducto, nombre, tiempoPreparacion, zona 
                                                       FROM producto 
                                                       WHERE idProducto = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductoNombre($nombreProducto) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * 
                                                       FROM producto 
                                                       WHERE nombre = :nombreProducto");
        $consulta->bindValue(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }

    public static function obtenerIdProducto($idProducto) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProducto
                                                       FROM producto 
                                                       WHERE idProducto = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($idProducto, $nombre, $tiempoPreparacion, $zona) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto
                                                      SET nombre = '{$nombre}', 
                                                          tiempoPreparacion = '{$tiempoPreparacion}', 
                                                          zona = {$zona}
                                                      WHERE idProducto = {$idProducto}");
        $consulta->execute();
    }

    public static function borrarProducto($idProducto) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET baja = :baja WHERE idProducto = {$idProducto}");
        $consulta->bindValue(':baja', true);
        $consulta->execute();
    }

    public function toStringCSV(){
        return $this->idProducto . ","  . $this->nombre . "," . $this->tiempoPreparacion . "," . $this->zona . "," . $this->baja . "\n";   
    }
}
