<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorGuiaRemision;
use Controladores\ControladorProductos;
use Controladores\ControladorInventarios;
use Controladores\ControladorVentas;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use api\ApiFacturacion;


class AjaxGuia
{
    public function ajaxBuscarUbigeoPartida()
    {
        $item = 'nombre_distrito';
        $valor = $_POST['ubigeopartida'];
        $respuesta = ControladorGuiaRemision::ctrMostrarUbigeo($item, $valor);

        foreach ($respuesta as $k => $v) {
            echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-ubigeo-partida'  idUbigeo='" . $v['ubigeo'] . "'>" . $v['ubigeo'] . ' - ' . $v['name'] . " - " . $v['nombre_provincia'] . " - " . $v['nombre_distrito'] . "</a></legend>";
        }
    }
    public function ajaxBuscarUbigeoLlegada()
    {
        $item = 'nombre_distrito';
        $valor = $_POST['ubigeollegada'];
        $respuesta = ControladorGuiaRemision::ctrMostrarUbigeo($item, $valor);

        foreach ($respuesta as $k => $v) {
            echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-ubigeo-llegada'  idUbigeo='" . $v['ubigeo'] . "'>" . $v['ubigeo'] . ' - ' . $v['name'] . " - " . $v['nombre_provincia'] . " - " . $v['nombre_distrito'] . "</a></legend>";
        }
    }

    public function ajaxAgregarUbigeo()
    {
        $tabla = 'ubigeo_distrito';
        $item = 'id';
        $valor = $_POST['codUbigeo'];

        $respuesta = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);
        echo json_encode($respuesta);
    }

    public function ajaxAgregarComprobante()
    {
        $tabla = 'venta';
        $valor = $_POST['serieCorrelativo'];
        $idsucursal = $_POST['idSucursal'];
        $respuesta = ControladorGuiaRemision::ctrBuscarSerieCorrelativo($tabla, $valor, $idsucursal);
        foreach ($respuesta as $k => $v) {
            echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-serie-correlativo'  numCorrelativo='" . $v['serie_correlativo'] . "'>" . $v['serie_correlativo'] . "</a></legend>";
        }
    }




    public $numCorrelativo;
    public function ajaxLlenarGuia()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $item = 'serie_correlativo';
        $valor = $this->numCorrelativo;

        $venta = ControladorVentas::ctrMostrarVentas($item, $valor);


        $item = "idventa";
        $valor = $venta['id'];

        $detalles = ControladorVentas::ctrMostrarDetalles($item, $valor);
        $_SESSION['carritoG'] = array();
        $carritoG = $_SESSION['carritoG'];
        //Asignamos a la variable $carro los valores guardados en la sessión
        unset($carritoG);

        if (!isset($_SESSION['carritoG'])) {
            $_SESSION['carritoG'] = array();
        }

        $carritoG = $_SESSION['carritoG'];

        //$item = count($carritoG)+1;


        foreach ($detalles as $k => $value) {
            $item = "id";
            $valor = $value['idproducto'];
            if ($emisor == 's') {
                $idsucursal = $venta['id_sucursal'];
                $producto = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);
            } else {
                @$producto = ControladorProductos::ctrMostrarProductosMultiAlmacen($item, $valor);
            }
            $item = count($carritoG) + 1;
            $existe = false;

            foreach ($carritoG as $k => $v) {

                if ($v['id'] == $producto['id']) {
                    $item = $k;
                    $existe = true;
                    break;
                }
            }



            $carritoG[$item] = array(
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'descripcion' => $producto['descripcion'],
                'unidad' => $producto['codunidad'],
                'cantidad' => $value['cantidad']

            );
        }

        $_SESSION['carritoG'] = $carritoG;

        $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
    }
    public function ajaxLlenaCarroGuia()
    {
        $series = '';
        if(isset($_POST['serieG'])){
            $series = $_POST['serieG'];
        }
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];

        $item = 'id';
        $valor = $idProducto;
        $idsucursal = $_POST['idSucursal'];
        $producto = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);
        if (!isset($_SESSION['carritoG'])) {
            $_SESSION['carritoG'] = array();
        }

        $carritoG = $_SESSION['carritoG'];

        //$item = count($carritoG)+1;
        if ($_POST['cantidad'] != null) {
            $item = count($carritoG) + 1;
            foreach ($carritoG as $k => $v) {
                if ($v['id'] == $producto['id']) {
                    $item = $k;
                    $existe = true;
                    break;
                }
            }



            $carritoG[$item] = array(
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'descripcion' => $producto['descripcion'],
                'unidad' => $producto['codunidad'],
                'cantidad' => $cantidad,

            );
        }

        $_SESSION['carritoG'] = $carritoG;

        $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
    }
    //ELIMINAR UN ITEM DEL CARRITO
    public $idEliminarCarro;
    public function ajaxEliminarItemCarritoG()
    {
        $idEliminarCarro = $this->idEliminarCarro;

        //Como antes, usamos extract() por comodidad, pero podemos no hacerlo tranquilamente
        $carritoG = $_SESSION['carritoG'];
        //Asignamos a la variable $carro los valores guardados en la sessión
        unset($carritoG[$idEliminarCarro]);
        $carritoG = array_values($carritoG);
        $_SESSION['carritoG'] = $carritoG;
        $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
    }

    public function ajaxCrearGuia()
    {

        $datosForm = $_POST;
        // var_dump($datosForm);
        // exit();
        $respuesta =  ControladorGuiaRemision::ctrCrearGuia($datosForm);
        return $respuesta;
    }
    public function ajaxRetornarAlmacen()
    {
        $sucursal = ControladorSucursal::ctrSucursal();

        $tabla = 'guia';
        $item = 'id';
        $valor = $_POST['idGuia'];
        $guia = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);
        $id_sucursal = $guia['id_sucursal'];

        $tabla = 'guia_detalle';
        $item = 'id_guia';
        $valor = $guia['id'];
        $detallesguia = ControladorGuiaRemision::ctrMostrarDetalles($tabla, $item, $valor);
        $comprobante = array(
            'serie' => $guia['serie'],
            'correlativo'  => $guia['correlativo'],
            'codvendedor' => $_SESSION['id']
        );


        $detalle = array();

        foreach ($detallesguia as $k => $v) {
            $itemx = array(
                'cantidad' => $v['cantidad'],
                'id_producto' => $v['id_producto'],
                'id' => $v['id_producto'],

            );
            $itemx;

            $detalle[] = $itemx;
        }
        $valor = $guia['id'];
        $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);

        $devolverInventario = ControladorInventarios::ctrNuevaDevolucionGuia($detalle, $comprobante, $id_sucursal);

        $datos = array(
            'id' => $guia['id'],
            'retorno' => 's'
        );
        var_dump($datos);
        $desactivarRetornoGuia = ControladorGuiaRemision::ctrDesactivarRetorno($datos);
    }

    public function ajaxGetCDR()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $token_result = ApiFacturacion::ObtenerToken($emisor);
        // var_dump($token_result);
        $token = $token_result->access_token;
        // echo $token;

        $idGuia = $_POST['idgetGuia'];
        $tabla = 'guia';
        $item = 'id';
        $valor = $_POST['idgetGuia'];
        $guia = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);
        $ticket = $guia['ticket'];
        $nombre = $emisor['ruc'] . '-' . $guia['tipodoc'] . '-' . $guia['serie'] . '-' . $guia['correlativo'];
        $nombre_archivo = $nombre . '.zip';
        $ruta_archivo_xml = "../api/xml/";
        $ruta_archivo_cdr = "../api/cdr/";
        $obtenerCdr = new ApiFacturacion();
        $obtenerCdr->ConsultarTicketGuiaRemision($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr);
        if (!empty($obtenerCdr)) {
            $codigosSunat = array(
                "feestado" => $obtenerCdr->codrespuesta,
                "fecodigoerror"  => $obtenerCdr->coderror,
                "femensajesunat"  => $obtenerCdr->mensajeError,
                "xmlbase64"  => $obtenerCdr->xmlb64,
                "cdrbase64"  => $obtenerCdr->cdrb64,
            );
        }
        // var_dump($codigosSunat);
        $guiaActualizar = ControladorGuiaRemision::ctrActualizarCDR($idGuia, $codigosSunat);
    }

}

if (isset($_POST['modalidadTraslado'])) {
    $objGuia = new AjaxGuia();
    $objGuia->ajaxCrearGuia();
}
if (isset($_POST['ubigeopartida'])) {
    $objGuia = new AjaxGuia();
    $objGuia->ajaxBuscarUbigeoPartida();
}
if (isset($_POST['ubigeollegada'])) {
    $objGuia = new AjaxGuia();
    $objGuia->ajaxBuscarUbigeoLlegada();
}
if (isset($_POST['codUbigeo'])) {
    $objGuia = new AjaxGuia();
    $objGuia->ajaxAgregarUbigeo();
}
if (isset($_POST['numCorrelativo'])) {

    $objSerieNota = new AjaxGuia();
    $objSerieNota->numCorrelativo = $_POST['numCorrelativo'];
    $objSerieNota->ajaxLlenarGuia();
}
if (isset($_POST['idProducto'])) {

    $objSerieNota = new AjaxGuia();
    $objSerieNota->ajaxLlenaCarroGuia();
}
if (isset($_POST['serieCorrelativo'])) {
    $objSerieNota = new AjaxGuia();
    $objSerieNota->ajaxAgregarComprobante();
}
// ELIMINAR ITEM DEL CARRO
if (isset($_POST['idEliminarCarro'])) {
    $objEliminarItemCarro = new AjaxGuia();
    $objEliminarItemCarro->idEliminarCarro = $_POST['idEliminarCarro'];
    $objEliminarItemCarro->ajaxEliminarItemCarritoG();
}
if (isset($_POST['idGuia'])) {
    $objretornar = new AjaxGuia();
    $objretornar->ajaxRetornarAlmacen();
}
if (isset($_POST['idgetGuia'])) {
    $objretornar = new AjaxGuia();
    $objretornar->ajaxGetCDR();
}
