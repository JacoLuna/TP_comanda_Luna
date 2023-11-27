<?php
class Encuesta {
    public $idEncuesta;
    public $idPedido;
    public $mesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $encuesta;

    public function crearEncuesta() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO Encuesta (idEncuesta,idPedido,mesa,restaurante,mozo,cocinero,encuesta) 
        VALUES (:idEncuesta,:idPedido,:mesa,:restaurante,:mozo,:cocinero,:encuesta)");
        $consulta->bindValue(':idEncuesta', '',PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $this->encuesta, PDO::PARAM_STR);
        
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    
    public static function obtenerUnaEncuesta($idEncuesta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                       FROM Encuesta
                                                       WHERE idEncuesta = {$idEncuesta}");
        $consulta->execute();
        return $consulta->fetchObject('Encuesta');
    }
    
    public static function obtenerIdEncuesta($idEncuesta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idEncuesta
                                                       FROM Encuesta 
                                                       WHERE idEncuesta = {$idEncuesta}");
        $consulta->execute();
        return $consulta->fetchObject('Encuesta');
    }
    
    public static function modificarEncuesta() {
    }

    public static function borrarEncuesta($idEncuesta) {
    }
}
