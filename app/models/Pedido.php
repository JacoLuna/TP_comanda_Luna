<?php


class Pedido {
    public $idPedido;
    public $idMesa;
    public $estado;
    public $nombreCliente;
    public $horaHecho;
    public static $estadosDisponibles = [
        "pendiente",            //solo mozos y socios pueden settear este estado
        "en preparación",       //solo empleados de cocina, barra y socios pueden settear este estado
        "listo para servir",    //solo empleados de cocina, barra y socios pueden settear este estado
        "servido"];             //solo mozos y socios pueden settear este estado

    public function crearPedido() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
        "INSERT INTO pedido (idPedido, estado, idMesa, nombreCliente, horaHecho) 
         VALUES (:idPedido, :estado, :idMesa, :nombreCliente, :horaHecho)");
        $idPedido = $this->generarCodigo(3);
        $consulta->bindValue(':idPedido', $idPedido ,PDO::PARAM_STR);
        $consulta->bindValue(':estado', Pedido::$estadosDisponibles[0], PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':horaHecho', $this->horaHecho->format("H:i:s"), PDO::PARAM_STR);
        $consulta->execute();

        return $idPedido;
    }

    public static function obtenerTodos($estado = "") {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($estado == ""){
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
        }else{
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE estado = '{$estado}'");
        }
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

    public static function obtenerProductosDelPedido($idPedido, $rol) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $zona = "";
        switch($rol){
            case "bartender-bebidas":
                $zona = "barra de choperas";
            break;
            case "bartender-tragos":
                $zona = "barra de tragos y vinos";
            break;
            case "cocinero-postres":
                $zona = "Candy Bar";
            break;
            case "cocinero-comida":
                $zona = "cocina";
            break;
        }

        if($zona != ""){
            $consulta = $objAccesoDatos->prepararConsulta("
            SELECT 
            ped.idPedido as Pedido , ped.idMesa as Mesa, idProductopedido as Nro_detalle, 
            ped.estado as estado_pedido, prodped.estado as estado_item, nombre as nombre_del_prodcuto, cant, prodped.tiempoPreparacion
            FROM productopedido as ped
            inner join productopedido as prodPed
            on ped.idPedido = prodPed.idPedido
            inner join producto as prod
            on prodPed.idProducto = prod.idProducto
            where :idPedido = ped.idPedido 
            and zona = '{$zona}'");
        }else{
            $consulta = $objAccesoDatos->prepararConsulta("
            SELECT *
            FROM productopedido
            where idPedido = :idPedido");
        }

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

    public static function demora($idPedido, $idMesa){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT TIMEDIFF( ped.horaHecho, max(prodPed.tiempoPreparacion)) as hora_de_pedido
                                                       FROM pedido as ped 
                                                       INNER JOIN productopedido as prodPed
                                                       on ped.idPedido = prodPed.idPedido
                                                       WHERE ped.idPedido = '{$idPedido}'
                                                       AND ped.idMesa = {$idMesa}");

        $consulta->execute();
        return $consulta->fetch();
    }
    public static function borrarMesa($idPedido) {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET rota = :rota WHERE idPedido = '{$idPedido}'");
        // $consulta->bindValue(':rota', true);
        // $consulta->execute();
    }
}
