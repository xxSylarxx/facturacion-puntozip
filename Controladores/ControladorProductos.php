<?php

namespace Controladores;

use Modelos\ModeloProductos;

class ControladorProductos
{
    // MOSTRAR ProductoS|
    public static function ctrMostrarTodosProductos($item, $valor)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarTodosProductos($tabla, $item, $valor);
        return $respuesta;
    }
    
    // MOSTRAR ProductoS|
    public static function ctrMostrarProductos($item, $valor, $idsucursal)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $idsucursal);
        return $respuesta;
    }

    public static function ctrMostrarProductosMultiAlmacen($item, $valor)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarProductosMultiAlmacen($tabla, $item, $valor);
        return $respuesta;
    }
    // MOSTRAR ProductoS|
    public static function ctrMostrarProductosTotal($item, $valor, $query)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarProductosTotal($tabla, $item, $valor, $query);
        return $respuesta;
    }
    // MOSTRAR ProductoS|
    public static function ctrMostrarProductosMasVendidos($item, $valor, $orden)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarProductosMasVendidos($tabla, $item, $valor, $orden);
        return $respuesta;
    }
    // LISTAR UNIDADES DE MEDIDA
    public static function ctrMostrarUnidade($item, $valor)
    {

        $tabla = 'unidad';
        $respuesta = ModeloProductos::mdlMostrarProductosUnidades($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarCategorias($item, $valor)
    {

        $tabla = 'categorias';
        $respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, null);
        return $respuesta;
    }
    // CREAR PRODUCTO
    public static function ctrCrearProducto($productos, $file)
    {
        if (isset($productos['nuevaSucursal']) && !empty($productos['nuevaSucursal'])) {
            if (isset($productos['nuevaDescripcion'])) {

                if (isset($productos['nuevoCodigo']) && strlen($productos['nuevoCodigo']) > 4) {
                    if (!empty($productos['nuevaCategoria'])) {
                        //  VALIDAR IMAGEN   
                        $rutabd = "vistas/img/productos/default/anonymous.png";
                        if (isset($file["nuevaImagen"]["tmp_name"]) && !empty($file["nuevaImagen"]["tmp_name"])) {

                            list($ancho, $alto) = getimagesize($file["nuevaImagen"]["tmp_name"]);
                            $nuevoAncho = 500;
                            $nuevoAlto = 500;

                            //CARPETA DONDE SE GUARDARÁ LA IMAGEN
                            $directorio = dirname(__FILE__) . "/../vistas/img/productos/" . $productos['nuevoCodigo'];
                            // mkdir($directorio, 0755);
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0755, true);
                            }

                            if ($file["nuevaImagen"]["type"] == "image/jpeg") {

                                $aleatorio = mt_rand(100, 999);
                                $ruta = dirname(__FILE__) . "/../vistas/img/productos/" . $productos['nuevoCodigo'] . "/" . $aleatorio . ".jpeg";
                                $rutabd = "vistas/img/productos/" . $productos['nuevoCodigo'] . "/" . $aleatorio . ".jpeg";

                                $origen = imagecreatefromjpeg($file["nuevaImagen"]["tmp_name"]);
                                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                                imagejpeg($destino, $ruta);
                            }
                            if ($file["nuevaImagen"]["type"] == "image/png") {

                                $aleatorio = mt_rand(100, 999);
                                $ruta = dirname(__FILE__) . "/../vistas/img/productos/" . $productos['nuevoCodigo'] . "/" . $aleatorio . ".png";
                                $rutabd = "vistas/img/productos/" . $productos['nuevoCodigo'] . "/" . $aleatorio . ".png";

                                $origen = imagecreatefrompng($file["nuevaImagen"]["tmp_name"]);
                                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                                imagepng($destino, $ruta);
                            }
                        }

                        //$ruta = "vistas/img/productos/default/anonymous.png";
                        $tabla = "productos";
                        $datos = array(
                            "id_categoria" => $productos['nuevaCategoria'],
                            "codigo" => $productos['nuevoCodigo'],
                            "serie" => $productos['nuevaSerie'],
                            "codigoafectacion" => $productos['tipo_afectacion'],
                            "unidad" => $productos['unidad'],
                            "descripcion" => $productos['nuevaDescripcion'],
                            "stock" => $productos['nuevoStock'],
                            'caracteristica' => $productos['nuevaCaracteristica'],
                            "tipo_precio" => '01',
                            "imagen" => $rutabd,
                            "id_sucursal"  => $productos['nuevaSucursal']
                        );

                        $respuesta = ModeloProductos::mdlCrearProducto($tabla, $datos);

                        return $respuesta;
                    } else {
                        echo "<script>
                        Swal.fire({
                            title: '¡LLene todo los campos!',
                            text: '...',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Cerrar'
                       
                        })
                        </script>";
                    }
                } else {
                    echo "<script>
                    Swal.fire({
                        title: '¡Debe generar o escanear un código!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    })
                    </script>";
                }
            }
        } else {
            echo "<script>
                    Swal.fire({
                        title: '¡Debe seleccionar un almacén!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    })
                    </script>";
        }
    }
    // EDITAR PRODUCTO
    public  function ctrEditarProducto()
    {

        if (isset($_POST['editarDescripcion'])) {


            //  VALIDAR IMAGEN   
            $ruta = $_POST['imagenActual'];
            if (isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])) {

                list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);
                $nuevoAncho = 500;
                $nuevoAlto = 500;

                //CARPETA DONDE SE GUARDARÁ LA IMAGEN
                $directorio = "vistas/img/productos/" . $_POST['editarCodigo'];
                if (empty($_POST['imagenActual']) && $_POST['imagenActual'] != "vistas/img/productos/default/anonymous.png") {

                    unlink($_POST['imagenActual']);
                } else {
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0755);
                    }
                }

                if ($_FILES["editarImagen"]["type"] == "image/jpeg") {

                    $aleatorio = mt_rand(100, 999);
                    $ruta = "vistas/img/productos/" . $_POST['editarCodigo'] . "/" . $aleatorio . ".jpeg";

                    $origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta);
                }
                if ($_FILES["editarImagen"]["type"] == "image/png") {

                    $aleatorio = mt_rand(100, 999);
                    $ruta = "vistas/img/productos/" . $_POST['editarCodigo'] . "/" . $aleatorio . ".png";

                    $origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta);
                }
            }

            //$ruta = "vistas/img/productos/default/anonymous.png";
            $tabla = "productos";
            $datos = array(
                "id" => $_POST['editarid'],
                "id_categoria" => $_POST['editarCategoria'],
                "codigo" => $_POST['editarCodigo'],
                "serie" => $_POST['editarSerie'],
                "codigoafectacion" => $_POST['editarAfectacion'],
                "unidad" => $_POST['editarUnidadMedida'],
                "descripcion" => $_POST['editarDescripcion'],
                'caracteristica' => $_POST['editarCaracteristica'],
                "stock" => $_POST['editarStock'],
                "imagen" => $ruta,
                "id_sucursal" => $_POST['editarSucursal']
            );

            $respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

            if ($respuesta == 'ok') {
                echo "<script>
                            Swal.fire({
                                title: '¡El producto ha sido actualizado corréctamente!',
                                text: '...',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Cerrar'
                            })
                            if(window.history.replaceState){
                                window.history.replaceState(null,null, window.location.href);
                                }
                            </script>";
            }
        }
    }

    // ELIMINAR PRODUCTO 
    public static function ctrEliminarProducto($datosEliminar)
    {

        if (isset($datosEliminar['idProducto'])) {
            $tabla = "productos";
            $datos = $datosEliminar['idProducto'];
            if (isset($datosEliminar['imagen']) && $datosEliminar['imagen'] != "vistas/img/productos/default/anonymous.png") {
                
                if(file_exists($datosEliminar['imagen'])){
                unlink($datosEliminar['imagen']);
                rmdir("vistas/img/productos/" . $datosEliminar['codigo']);
                }
            }

            $respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);
            if ($respuesta == 'ok') {

                echo "success";
            } else {
                echo "error";
            }
        }
    }
    // FIN ELIMINAR PRODUCTO:

    public  function ctrListarProductos()
    {

        $respuesta = ModeloProductos::mdlListarProductos();
        echo $respuesta;
    }
    public  function ctrListarProductosVentas()
    {

        $respuesta = ModeloProductos::mdlListarProductosVentas();
        echo $respuesta;
    }
    public  function ctrListarProductosGuia()
    {

        $respuesta = ModeloProductos::mdlListarProductosGuia();
        echo $respuesta;
    }
    // ACTIVAR Y DESACTIVAR  UNIDAD DE MEDIDA
    public static function ctrActivaDesactivaUnidadMedida($datos)
    {
        $tabla = "unidad";
        $respuesta = ModeloProductos::mdlActivaDesactivaUnidadMedida($tabla, $datos);
        return $respuesta;
    }
    // ACTIVAR Y DESACTIVAR  PRODUCTOS
    public static function ctrActivaDesactivaProducto($datos)
    {
        $tabla = "productos";
        $respuesta = ModeloProductos::mdlActivaDesactivaProducto($tabla, $datos);
        return $respuesta;
    }
    public static function ctrActualizarStock($detalle, $valor)
    {
        $tabla = "productos";
        $respuesta = ModeloProductos::mdlActualizarStock($tabla, $detalle, $valor);
        return $respuesta;
    }

    public static function ctrActualizarStockPorAjuste($datos)
    {
        $respuesta = ModeloProductos::mdlActualizarStockPorAjuste($datos);
        return $respuesta;
    }


    public static function ctrActualizarMasVendidos($detalle, $valor)
    {
        $tabla = "productos";
        $respuesta = ModeloProductos::mdlActualizarMasVendido($tabla, $detalle, $valor);
        return $respuesta;
    }

    public static function ctrMostrarProductosStock($item, $valor)
    {

        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);
        return $respuesta;
    }

    public  static function ctrCambiarFoto($datos)
    {
        $tabla = 'productos';
        $respuesta = ModeloProductos::mdlCambiarFoto($tabla, $datos);
        return $respuesta;
    }

    public static function ctrObtenerUltimoProductoId(){
        $respuesta = ModeloProductos::mdlObtenerUltimoProductoId();
        return $respuesta;
    }

    public static function ctrCrearSeries($datosSeries, $idProducto){
        $tabla = 'series_productos';
        $respuesta = ModeloProductos::mdlCrearSeries($tabla, $datosSeries, $idProducto);
        return $respuesta;
    }

       // MOSTRAR SERIES PRODUCTOS
       public static function ctrMostrarSeriesProductos($item, $valor)
       {
   
           $tabla = 'series_productos';
           $respuesta = ModeloProductos::mdlMostrarSeriesProductos($tabla, $item, $valor);
           return $respuesta;
       }
        public static function ctrMostrarSeriesProductosActualizar($item, $valor)
       {
   
           $tabla = 'series_productos';
           $respuesta = ModeloProductos::mdlMostrarSeriesProductosActualizar($tabla, $item, $valor);
           return $respuesta;
       }
          
       
       public static function ctrMostrarSeriesProductosGuias($item, $valor)
       {
   
           $tabla = 'series_productos';
           $respuesta = ModeloProductos::mdlMostrarSeriesProductosGuias($tabla, $item, $valor);
           return $respuesta;
       }

       public static function ctrActualizarSerie($idSerie, $item, $valor){

        $tabla = 'series_productos';
        $respuesta = ModeloProductos::mdlActualizarSerie($tabla, $idSerie, $item, $valor);
        return $respuesta;
       }

       public static function ctrEliminrSerie($idSerie){

        $tabla = 'series_productos';
        $respuesta = ModeloProductos::mdlEliminarSerie($tabla, $idSerie);
        return $respuesta;
       }
}
