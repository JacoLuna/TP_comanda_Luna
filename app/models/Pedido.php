<?php


class Pedido {
    public $idPedido;
    public $idMesa;
    public $estado;
    public $nombreCliente;
    public static $estadosDisponibles = [
        "pendiente",            //solo mozos y socios pueden settear este estado
        "en preparación",       //solo empleados de cocina, barra y socios pueden settear este estado
        "listo para servir",    //solo empleados de cocina, barra y socios pueden settear este estado
        "servido"];             //solo mozos y socios pueden settear este estado

    public function crearPedido() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idPedido, idMesa, estado, nombreCliente) 
        VALUES (:idPedido, :idMesa, :estado, :nombreCliente)");
        $consulta->bindValue(':idPedido', $this->generarCodigo(3),PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', Pedido::$estadosDisponibles[1], PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($idPedido) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, idMesa, estado 
                                                       FROM pedido 
                                                       WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        
        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerProdcutosDelPedido($idPedido) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
        SELECT ped.idPedido, ped.idMesa, ped.estado , nombre as nombre_del_prodcuto 
        FROM pedido as ped
        inner join productopedido as prodPed
        on ped.idPedido = prodPed.idPedido
        inner join producto as prod
        on prodPed.idProducto = prod.idProducto
        where :idPedido = ped.idPedido");

        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
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
    public static function obtenerIdPedido($idPedido) {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido
                                                       FROM pedido 
                                                       WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }
    public static function obtenerUltimoId(){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(idPedido) as ultimoId FROM pedido");
            $consulta->execute();
            return $consulta->fetchObject('Pedido');
    }
    public static function modificarPedido($idPedido, $estado = "", $nombreCliente = "") {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        if($estado == ""){
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido
                                                          SET nombreCliente = '{$nombreCliente}'
                                                          WHERE idPedido = '{$idPedido}'");
        }else{
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido
                                                          SET estado = '{$estado}'
                                                          WHERE idPedido = '{$idPedido}'");
        }
        $consulta->execute();
    }

    public static function generarCodigo(){
        do{

            $codigo = "";
            $primerChar = rand(0,9);
            $segundoChar = rand(0,9);
            $codigo = strval($primerChar) . strval($segundoChar);
            $codigo .= Utilities::generateRandomString(3);

            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("SELECT count(idPedido) as cont
                                                        FROM pedido 
                                                        WHERE idPedido = '{$codigo}'");
            $consulta->execute();
            $resultado = $consulta->fetch();
        }while($resultado['cont'] != 0);

        return $codigo;
    }

    public static function borrarMesa($idPedido) {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET rota = :rota WHERE idPedido = '{$idPedido}'");
        // $consulta->bindValue(':rota', true);
        // $consulta->execute();
    }
}
