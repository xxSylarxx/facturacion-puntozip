<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorProductos;
use Controladores\ControladorEmpresa;
use Controladores\ControladorVentas;

class AjaxProductos
{


    public function ajaxCrearProducto()
    {

        $productos = $_POST;
        $file = $_FILES;
        $respuesta = ControladorProductos::ctrCrearProducto($productos, $file);
        // var_dump($_POST['seriep']);
        if ($respuesta == 'ok') {
            if (isset($_POST['agregarSerie']) && $_POST['agregarSerie'] == 'si') {
                $idUltimoProducto = ControladorProductos::ctrObtenerUltimoProductoId();
                $idProducto = $idUltimoProducto['id'];
                $datosSeriesU = array_map('mb_strtoupper', $_POST['seriep']);
                $datosSeries = array_map('trim', $datosSeriesU);
                $creatSeriesProductos = ControladorProductos::ctrCrearSeries($datosSeries, $idProducto);
            }
        }
        echo $respuesta;
    }
    public $idCategoria;
    public function ajaxCrearCodigoProducto()
    {

        $item = "id_categoria";
        $valor = $this->idCategoria;
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, null);

        echo json_encode($respuesta);
    }
    // EDITAR PRODUCTO Y SELECCIONAR PRODUCTO
    public $idProducto;
    public function ajaxAgregarProducto()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        if ($_SESSION['perfil'] == 'Administrador') {
            if (isset($_POST['idsucursal']) && !empty($_POST['idsucursal'])) {
                $query = "id_sucursal =  " . $_POST['idsucursal'] . "  AND";
            } else {
                $query = '';
            }
        } else {
            if ($emisor['multialmacen'] == 's') {
                $query = "id_sucursal = " . $_SESSION['id_sucursal'] . " AND";
            } else {
            }
            $query = '';
        }


        $item = "id";
        $valor = $this->idProducto;
        $respuesta = ControladorProductos::ctrMostrarProductosTotal($item, $valor, $query);
        echo json_encode($respuesta);
    }
    //GENERAR CÓDIGO BARRA
    public function ajaxGenerarCodigoBarra($cantidad, $longitud, $incluyeNum = true)
    { {
            $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            if ($incluyeNum)
                $caracteres .= "1234567890";

            $arrPassResult = array();
            $index = 0;
            while ($index < $cantidad) {
                $tmp = "";
                for ($i = 0; $i < $longitud; $i++) {
                    $tmp .= $caracteres[rand(0, strlen($caracteres) - 1)];
                }
                if (!in_array($tmp, $arrPassResult)) {
                    $arrPassResult[] = $tmp;
                    $index++;
                }
            }
            echo $tmp;
        }
    }
    // VALIDAR PRODUCTO CODIGO
    public function ajaxAValidarProducto()
    {

        $idsucursal = $_POST['idSucursal'];
        $item = "codigo";
        $valor = trim($_POST['codigoValidar']);
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);


        echo json_encode($respuesta);
    }    // SELECCIONAR PRODUCTO CODIGO BARRA
    public function ajaxATraerProducto()
    {

        $idsucursal = $_POST['idSucursal'];
        $item = "codigo";
        $valor = trim($_POST['codigobarra']);
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);


        echo json_encode($respuesta);
    }
    public function ajaxATraerProductoInventario()
    {

        $idsucursal = $_POST['idSucursalpro'];
        $item = null;
        $valor = null;
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);

        foreach ($respuesta as $k => $v) {
            echo '<option value="' . $v['id'] . '">' . $v['descripcion'] . '</option>';
        }
    }
    //  ELIMINAR PRODUCTO BETA
    public $idEliminarProducto;
    public $codigo;
    public $imagen;
    public function ajaxEliminarProducto()
    {
        $tabla = 'detalle';
        $item = 'idproducto';
        $valor = $this->idEliminarProducto;
        $detalles = ControladorVentas::ctrMostrarVentasParaConsultar($tabla, $item, $valor);

        $datosEliminar = array(
            'idProducto' => $this->idEliminarProducto,
            'codigo' => $this->codigo,
            'imagen' => $this->imagen
        );
        if (!empty($detalles)) {
            echo 'ESTE PRODUCTO O SERVICIO NO PUEDE SER ELIMINADO PORQUE TIENE COMPROBANTES RELACIONADOS';
        } else {
            $resultado = ControladorProductos::ctrEliminarProducto($datosEliminar);
        }
    }

    public function ajaxActivaDesactivaUnidadMedida()
    {
        $datos = array(
            "id" => $_POST['idUnidad'],
            "modo" => $_POST['modo']
        );
        $resultado = ControladorProductos::ctrActivaDesactivaUnidadMedida($datos);
    }
    public function ajaxActivaDesactivaProducto()
    {
        $datos = array(
            "id" => $_POST['id_productoa'],
            "modo" => $_POST['activos']
        );
        $resultado = ControladorProductos::ctrActivaDesactivaProducto($datos);
    }

    public function ajaxAgregarSeries()
    {
        // var_dump($_POST);
        // exit();
       
        $idProducto = $_POST['idproductoSnuevo'];
        $datosSeriesU = array_map('mb_strtoupper', $_POST['seriepn']);
        $datosSeries = array_map('trim', $datosSeriesU);

       echo $creatSeriesProductos = ControladorProductos::ctrCrearSeries($datosSeries, $idProducto);
        
    }
    public function ajaxActualizarSerie()
    {

        $idSerie = $_POST['idSerie'];
        if (isset($_POST['activoserie'])) {
            $item = 'disponible';
            $valor = $_POST['activoserie'];
        }
        if (isset($_POST['numserie'])) {
            $item = 'serie';
            $valor = mb_strtoupper($_POST['numserie']);
        }


        $resultado = ControladorProductos::ctrActualizarSerie($idSerie, $item, $valor);
        echo $resultado;
    }

    public function ajaxEliminarSerie(){

        $idSerie = $_POST['idSerieEliminar'];

        $respuesta = ControladorProductos::ctrEliminrSerie($idSerie);
        echo $respuesta;
    }
}
if (isset($_POST['codigobarra'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxATraerProducto();
}

if (isset($_POST['codigoValidar'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxAValidarProducto();
}
if (isset($_POST['codigobr'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxGenerarCodigoBarra(1, 7);
}
if (isset($_POST['nuevaDescripcion'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxCrearProducto();
}
// GENERAR CÓDIGO DESDE LA idCategoria
if (isset($_POST['idCategoria'])) {
    $codigoProducto = new AjaxProductos();
    $codigoProducto->idCategoria = $_POST['idCategoria'];
    $codigoProducto->ajaxCrearCodigoProducto();
}
// EDITAR PRODUCTO
if (isset($_POST['idProducto'])) {
    $editarProducto = new AjaxProductos();
    $editarProducto->idProducto = $_POST['idProducto'];
    $editarProducto->ajaxAgregarProducto();
}
// ELIMINAR PRODUCTO
if (isset($_POST['idEliminarProducto'])) {

    $eliminar = new AjaxProductos();
    $eliminar->idEliminarProducto = $_POST['idEliminarProducto'];
    $eliminar->codigo = $_POST['codigo'];
    $eliminar->imagen = $_POST['imagen'];
    $eliminar->ajaxEliminarProducto();
}
if (isset($_POST['idUnidad'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxActivaDesactivaUnidadMedida();
}
if (isset($_POST['id_productoa'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxActivaDesactivaProducto();
}

if (isset($_POST['idSucursalpro'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxATraerProductoInventario();
}
if (isset($_POST['activoserie'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxActualizarSerie();
}
if (isset($_POST['numserie'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxActualizarSerie();
}

if (isset($_POST['idproductoSnuevo'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxAgregarSeries();
}

if (isset($_POST['idSerieEliminar'])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxEliminarSerie();
}
