<?php

class Mesa {
    public $idMesa;
    public $idPersonal;
    public $cantComensales;
    public $codigo;
    public $rota = false;

    public function crearMesa() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO Mesa (idMesa, idPersonal, cantComensales, codigo, rota) 
        VALUES (:idMesa, :idPersonal, :cantComensales, :codigo, :rota)");
        $consulta->bindValue(':idMesa', '',PDO::PARAM_INT);
        $consulta->bindValue(':idPersonal', $this->idPersonal, PDO::PARAM_INT);
        $consulta->bindValue(':cantComensales', $this->cantComensales, PDO::PARAM_INT);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':rota', $this->rota, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
//CONTROLAR
    public static function obtenerMesa($codigo) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPersonal as 'mozo', cantComensales, codigo 
                                                       FROM mesa
                                                       WHERE codigo = '{$codigo}'");
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
//CONTROLAR
    public static function obtenerIdMesa($codigo) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa
                                                       FROM mesa 
                                                       WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }
//CONTROLAR
    public static function modificarMesa($idMesa, $idPersonal, $cantComensales, $codigo) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE idMesa
                                                      SET codigo = '{$codigo}',
                                                          cantComensales = {$cantComensales}
                                                      WHERE id = {$idMesa}");
        $consulta->execute();
    }
//CONTROLAR
    public static function borrarMesa($codigo) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET rota = :rota WHERE codigo = {$codigo}");
        $consulta->bindValue(':rota', true);
        $consulta->execute();
    }
}
