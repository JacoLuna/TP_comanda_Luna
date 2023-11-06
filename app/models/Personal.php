<?php

class Personal {
    public $idPersonal;
    public $nombre;
    public $apellido;
    public $DNI;
    public $rol;
    public $fechaIngreso;
    public $fechaBaja;

    public function crearPersonal() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO personal (idPersonal, nombre, apellido, DNI, rol, fechaIngreso, fechaBaja) 
        VALUES (:idPersonal, :nombre, :apellido, :DNI, :rol, :fechaIngreso, :fechaIngreso, :fechaBaja)");
        // $consulta->bindValue(':idPersonal', $this->idPersonal, PDO::PARAM_STR);
        //tanto 0 como '' es valido para autoincrement
        // $consulta->bindValue(':idPersonal', 0,PDO::PARAM_STR);
        $consulta->bindValue(':idPersonal', '',PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':DNI', $this->DNI, PDO::PARAM_INT);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', $this->fechaBaja, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM personal");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Personal');
    }

    public static function obtenerPersonal($DNI) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPersonal, nombre, apellido, DNI, rol, fechaIngreso 
                                                       FROM personal 
                                                       WHERE DNI = :DNI");
        $consulta->bindValue(':DNI', $DNI, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Personal');
    }

    public static function obtenerIdPersonal($DNI) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPersonal
                                                       FROM personal 
                                                       WHERE DNI = :DNI");
        $consulta->bindValue(':DNI', $DNI, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Personal');
    }

    public static function modificarPersonal($idPersonal, $nombre, $apellido, $DNI, $rol, $fechaIngreso) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE personal
                                                      SET nombre = '{$nombre}', 
                                                          apellido = '{$apellido}', 
                                                          DNI = {$DNI}, 
                                                          rol = '{$rol}', 
                                                          fechaIngreso = '{$fechaIngreso}'
                                                      WHERE idPersonal = {$idPersonal}");
        $consulta->execute();
    }

    public static function borrarPersonal($DNI) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE personal SET fechaBaja = :fechaBaja WHERE DNI = {$DNI}");
        // $fecha = new DateTime(date("d-m-Y"));
        $fecha = new DateTime();
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
