<?php
class Mesa {
    public $idMesa;
    public $idPersonal;
    public $cantComensales;
    public $estado;
    public $rota = false;
    public $primera = true;

    public static $estadosDisponibles = [
        "con cliente esperando pedido",
        "con cliente comiendo",
        "con cliente pagando",
        "cerrada"
    ];

    public function crearMesa() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Mesa (idMesa, idPersonal, cantComensales, rota, estado) 
        VALUES (:idMesa, :idPersonal, :cantComensales, :rota, :estado)"
        );

        if ($this->primera) {
            $consulta->bindValue(':idMesa', 10000, PDO::PARAM_INT);
        } else {
            $consulta->bindValue(':idMesa', '', PDO::PARAM_INT);
        }
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *
                                                       FROM mesa
                                                       WHERE idMesa = {$idMesa}");
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerIdMesa($idMesa) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa
                                                       FROM mesa 
                                                       WHERE idMesa = {$idMesa}");
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($idMesa, $estado = "", $idPersonal = -1, $cantComensales = -1) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        if ($estado == "" || $estado == Mesa::$estadosDisponibles[0]) {
            if ($idPersonal != -1 && $cantComensales != -1 && $estado == Mesa::$estadosDisponibles[0]) {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                              SET idPersonal = {$idPersonal},
                                                                  cantComensales = {$cantComensales},
                                                                  estado = '{$estado}'
                                                              WHERE idMesa = {$idMesa}");
            } else {
                if ($idPersonal != -1 && $cantComensales != -1) {
                    $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                                  SET idPersonal = {$idPersonal},
                                                                      cantComensales = {$cantComensales}
                                                                  WHERE idMesa = {$idMesa}");
                } else {
                    if ($cantComensales != -1) {
                        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                                  SET cantComensales = {$cantComensales}
                                                                  WHERE idMesa = {$idMesa}");
                    } else {
                        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                                  SET idPersonal = {$idPersonal}
                                                                  WHERE idMesa = {$idMesa}");
                    }
                }
            }
        } else {
            if ($estado == Mesa::$estadosDisponibles[3]) {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                              SET estado = '{$estado}', 
                                                                  idPersonal = 1
                                                              WHERE idMesa = {$idMesa}");
            } else {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa
                                                              SET estado = '{$estado}'
                                                              WHERE idMesa = {$idMesa}");
            }
        }
        $consulta->execute();
    }

    public static function borrarMesa($idMesa) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET rota = :rota WHERE idMesa = {$idMesa}");
        $consulta->bindValue(':rota', true);
        $consulta->execute();
    }

    static public function esPrimeraTupla() {
        $primero = false;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT count(idMesa) as cantMesas FROM mesa");
        $consulta->execute();
        $rta = $consulta->fetch();
        if ($rta['cantMesas'] == 0) {
            $primero = true;
        }
        return $primero;
    }

    static public function obtenerMesaMasUsada() {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT IdMesa, MAX(cont) as cont
                                                      from (SELECT IdMesa, COUNT(IdMesa) as cont 
                                                            FROM pedido 
                                                            GROUP BY IdMesa) as a");
        $consulta->execute();
        return $consulta->fetchAll();
    }
}
