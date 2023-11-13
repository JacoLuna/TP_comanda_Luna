<?php
class Mesa {
    public $idMesa; //varchar
    public $idPersonal;
    public $cantComensales;
    public $estado;
    public $rota = false;

    public static $estadosDisponibles = [
        "con cliente esperando pedido",
        "con cliente comiendo",
        "con cliente pagando",
        "cerrada"];

    public function crearMesa() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO Mesa (idMesa, idPersonal, cantComensales, rota, estado) 
        VALUES (:idMesa, :idPersonal, :cantComensales, :rota, :estado)");

        $consulta->bindValue(':idMesa', $this->idMesa,PDO::PARAM_INT);
        $consulta->bindValue(':idPersonal', $this->idPersonal, PDO::PARAM_INT);
        $consulta->bindValue(':cantComensales', $this->cantComensales, PDO::PARAM_INT);
        $consulta->bindValue(':rota', $this->rota, PDO::PARAM_BOOL);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    
    public static function obtenerUnaMesa($idMesa) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, idPersonal as 'mozo', cantComensales 
                                                       FROM mesa
                                                       WHERE idMesa = '{$idMesa}'");
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
    
    public static function obtenerIdMesa($idMesa) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa
                                                       FROM mesa 
                                                       WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
    
    public static function modificarMesa($idMesa, $idPersonal, $cantComensales) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                      SET idPersonal = '{$idPersonal}',
                                                          cantComensales = {$cantComensales}
                                                      WHERE idMesa = '{$idMesa}'");
        $consulta->execute();
    }

    public static function borrarMesa($idMesa) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET rota = :rota WHERE idMesa = '{$idMesa}'");
        $consulta->bindValue(':rota', true);
        $consulta->execute();
    }
}
