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

    public static function mejoresComentarios(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $suma = 0;
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                       FROM encuesta");
        $consulta->execute();
        $resultados = $consulta->fetchAll();
        $suma = 0;
        $encuestasCuentas = array();
        $encuestas = array();
        $sumaPuntajes = 0;

        for($i = 0 ; $i < count($resultados) ; $i++){
            $encuestasCuentas[$i]['idPedido'] = $resultados[$i]['idPedido'];
            $encuestasCuentas[$i]['mesa'] = $resultados[$i]['mesa'];
            $encuestasCuentas[$i]['restaurante'] = $resultados[$i]['restaurante'];
            $encuestasCuentas[$i]['mozo'] = $resultados[$i]['mozo'];
            $encuestasCuentas[$i]['cocinero'] = $resultados[$i]['cocinero'];
            $encuestasCuentas[$i]['encuesta'] = $resultados[$i]['encuesta'];
            $encuestasCuentas[$i]['suma'] = $resultados[$i]['mesa'] + 
                                            $resultados[$i]['restaurante'] + 
                                            $resultados[$i]['mozo'] + 
                                            $resultados[$i]['cocinero'];
            $sumaPuntajes += $encuestasCuentas[$i]['suma'];
        }
        $promedio = $sumaPuntajes / count($resultados);

        $i = 0;
        foreach($encuestasCuentas as $encuesta){
            if($encuesta['suma'] > $promedio ){
                $encuestas[$i] = $encuesta;
                $i++;
            }
        }
        return $encuestas;
    }
}
