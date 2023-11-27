<?php
class Factura {
    public $idFactura;
    public $idPedido;
    public $propina;

    public function crearFactura() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO Factura (idFactura,idPedido,propina) 
        VALUES (:idFactura,:idPedido,:propina)");
        $consulta->bindValue(':idFactura', '',PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':propina', $this->propina, PDO::PARAM_INT);
        
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Factura");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
    }
    
    public static function obtenerUnaFactura($idFactura) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                       FROM Factura
                                                       WHERE idFactura = {$idFactura}");
        $consulta->execute();
        return $consulta->fetchObject('Factura');
    }
    
    public static function obtenerIdFactura($idFactura) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idFactura
                                                       FROM Factura 
                                                       WHERE idFactura = {$idFactura}");
        $consulta->execute();
        return $consulta->fetchObject('Factura');
    }
    
    public static function modificarFactura() {
    }

    public static function borrarFactura($idFactura) {
    }
}
