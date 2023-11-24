<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

use Psr\Http\Message\UploadedFileInterface;

class ProductoController extends Producto implements IApiUsable {
    static $productosStr = array();

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $zona = $parametros['zona'];

        $usr = new Producto();
        $usr->nombre = $nombre;
        $usr->tiempoPreparacion = $tiempoPreparacion;
        $usr->zona = $zona;
        $usr->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    static function cargarProductos($productos) {
        $prodStr = "";
        if ($productos != null) {
            for ($i = 0; $i < count($productos); $i++) {
                for ($j = 0; $j < count($productos[$i]); $j++) {

                    if ($j == count($productos[$i]) - 1) {
                        $prodStr .= $productos[$i][$j];
                    } else {
                        $prodStr .= $productos[$i][$j] . ",";
                    }
                }
                array_push(self::$productosStr, $prodStr);
                $prodStr = "";
            }
        }
    }

    public function cargarArchivoCsv($request, $response, $args) {
        $uploadedFile = $request->getUploadedFiles()['productos'];
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        
        if($extension === 'csv') {
            
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $rutaArchivo = $uploadedFile->getStream()->getMetadata('uri');
                if($this->CargarProductosDesdeCSV($rutaArchivo)) {
                    $payload = json_encode(array("mensaje" => "productos cargados correctamente."));
                }

                $payload = json_encode(array("mensaje" => "archivo cargado con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "Hubo un error al cargar el Archivo" . " " . $uploadedFile->getError()));
            }
        }else {
            $payload = json_encode(array("error" => "La extension del archivo debe ser csv"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    private function cargarProductosDesdeCSV($rutaArchivo) {
        $retorno = false;

        if (file_exists($rutaArchivo)) {
            $archivo = fopen($rutaArchivo, "r");
        
            if (file_get_contents($rutaArchivo) > 0) {
                $retorno = true;
                $indice = 0;

                while (!feof($archivo)) {
                    $vector = fgetcsv($archivo, filesize($rutaArchivo));
                    if($vector && $indice != 0){
                        $prod = new Producto();
                        $prod->idProducto = $vector[0];
                        $prod->nombre = $vector[1];
                        $prod->tiempoPreparacion = $vector[2];
                        $prod->zona = $vector[3];
                        $prod->baja = $vector[4];
                        $prod->crearProducto();
                    }
                    $indice++;
                }
                fclose($archivo);
            }
        } else {
            $this->guardarArchivoSCV($rutaArchivo);
        }
        return $retorno;
    }
    
    public function descargarArchivoCsv($request, $response, $args) {
        $data = $this->castProductosToCSV();
        
        if($data !== null) {
            $response = $response
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment;filename=productos.csv')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache');
            
            $response->getBody()->write($data);
            return $response;
        }
        else {
            $payload = json_encode(array("error" => "Error al descargar el archivo"));
            $response->getBody()->write($payload);
        }
    
        return $response;
    } 

    private static function castProductosToCSV() {
        $productos = Producto::obtenerTodos();
        foreach($productos as $index => $producto) {
            if($index == 0) $data = "idPersonal,nombre,tiempoPreparacion,zona,baja\n";
            $data .= $producto->toStringCSV();
        }
        return $data;
    }

    private function guardarArchivoSCV($rutaArchivo, $datos = array()) {
        $rows = "";
        if (!$archivo = fopen($rutaArchivo, "w")) {
            $archivo = fopen($rutaArchivo, "x+");
        }
        foreach($datos as $index=>$dato){
            if($index == count($datos) -1){
                $rows .= $dato;
            }else{
                $rows .= $dato . "\n";
            }
        }
        fwrite($archivo, $rows);
        fclose($archivo);
    }

    public function TraerUno($request, $response, $args) {
        $usr = $args['idProducto'];
        $producto = Producto::obtenerProducto($usr);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    private function TraerID($args) {
        $usr = $args['idProducto'];
        $codigo = Producto::obtenerIdProducto($usr);
        return $codigo;
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista), JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    //CONTROLAR
    public function ModificarUno($request, $response, $args) {
        $idPersonal = $this->TraerID($args);
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $zona = $parametros['zona'];

        Producto::modificarProducto($idPersonal->idPersonal, $nombre, $tiempoPreparacion, $zona);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $idPersonal = $this->TraerID($args);
        Producto::borrarProducto($idPersonal->idPersonal);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
