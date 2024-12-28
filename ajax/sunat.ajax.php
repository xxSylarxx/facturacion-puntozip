<?php
require_once "../vendor/autoload.php";

use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorSunat;
use Controladores\ControladorClientes;


class AjaxSunat
{


    public $codigoAfectacion;
    public function ajaxCodigoAfectacion()
    {

        $item = "codigo";
        $valor = $this->codigoAfectacion;
        $respuesta = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
        echo json_encode($respuesta);
    }

    public $codUnidad;
    public function ajaxCodigoUnidad()
    {

        $item = "codigo";
        $valor = $this->codUnidad;
        $respuesta = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
        echo json_encode($respuesta);
    }
       
    
    public function ajaxPorDetraccion()
    {

        $item = "codigo";
        $valor = $_POST['coddetraccion'];
        $respuesta = ControladorSunat::ctrMostrarTipoDetraccion($item, $valor);
        $totaldetraccion = round(($_POST['totales'] * $respuesta['porcentaje']) / 100 ,2);

        $datos = array(
            'porcentaje' => $respuesta['porcentaje'],
            'totaldetraccion' => $totaldetraccion
        );
        echo json_encode($datos);
    }
      public function ajaxPorDetraccionCalculo()
    {        
        $totaldetraccion = round(($_POST['totales'] * $_POST['porcentajeDetraccion']) / 100 ,2);

        $datos = array(
            'porcentaje' => $_POST['porcentajeDetraccion'],
            'totaldetraccion' => $totaldetraccion
        );
        echo json_encode($datos);
    }
    // OBTENER EL CORRELATIVO Y SUMAR 1
    public $idSerie;
    public function ajaxCorrelativo()
    {
        $item = "id";
        $valor = $this->idSerie;
        $respuesta = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

        if ($respuesta['correlativo'] == 0) {

            echo $respuesta['correlativo'] = 1;
        } else {

            echo $respuesta['correlativo'] + 1;
        }
    }

    // BUSCAR SERIE CORRELATIVO VENTA
    public $serieCorrelativo;
    public function ajaxBuscarSerieCorrelativo($tipocomp)
    {
        $valor = $this->serieCorrelativo;
        if ($tipocomp == "01") {
            $codigo = "01";
            $respuesta = ControladorSunat::ctrBuscarSerieCorrelativo($valor, $codigo);
        }
        if ($tipocomp == "03") {
            $codigo = "03";
            $respuesta = ControladorSunat::ctrBuscarSerieCorrelativo($valor, $codigo);
        }
        echo json_encode($respuesta);
    }

    public function ajaxCrearSeries()
    {
        var_dump($_POST);

        if ($_POST['ruta'] == 'crear-factura') {
            $valor = "01";
        }
        if ($_POST['ruta'] == 'crear-boleta') {
            $valor = "03";
        }
        if ($_POST['ruta'] == 'crear-nota') {
            $valor = "02";
        }

        if ($_POST['ruta'] == 'nota-credito') {
            $valor = "07";
        }
        if ($_POST['ruta'] == 'nota-debito') {
            $valor = "08";
        }
        if ($_POST['ruta'] == 'crear-cotizacion') {
            $valor = "00";
        }
        if ($_POST['ruta'] == 'crear-guia') {
            $valor = "09";
        }
        if ($_POST['ruta'] == 'pos') {
            $valor = $_POST['tipocomp'];
        }

        $id_sucursal = $_POST['idSucursal'];
        $serieComprobante = ControladorSunat::ctrMostrarSerie($valor, $id_sucursal);
        foreach ($serieComprobante as $key => $value) {
            echo '<option value=' . $value['id'] . ' id="idSerie">' . $value['serie'] . '</option>';
        }
    }
    //  public function ajaxConnection(){

    //     $respuesta = ControladorSunat::ctrConn();
    //     echo $respuesta;
    //  } 
}
if (isset($_POST['codigoAfectacion'])) {

    $objCodigoAfectacion = new AjaxSunat();
    $objCodigoAfectacion->codigoAfectacion = $_POST['codigoAfectacion'];
    $objCodigoAfectacion->ajaxCodigoAfectacion();
}
if (isset($_POST['codUnidad'])) {

    $objCodigoUnidad = new AjaxSunat();
    $objCodigoUnidad->codUnidad = $_POST['codUnidad'];
    $objCodigoUnidad->ajaxCodigoUnidad();
}
if (isset($_POST['idSerie'])) {

    $objCorrelativo = new AjaxSunat();
    $objCorrelativo->idSerie = $_POST['idSerie'];
    $objCorrelativo->ajaxCorrelativo();
}

if (isset($_POST['serieCorrelativo'])) {

    $objSerie = new AjaxSunat();
    $objSerie->serieCorrelativo = $_POST['serieCorrelativo'];
    $objSerie->ajaxBuscarSerieCorrelativo($_POST['tipoComprobante']);
}
// if(isset($_POST['conexion'])){

//     $objConn = new AjaxSunat();
//     $objConn->ajaxConnection();

// }

if (isset($_POST['idSucursal'])) {
    $objSerie = new AjaxSunat;
    $objSerie->ajaxCrearSeries();
}
if (isset($_POST['coddetraccion'])) {

    $objCodigoUnidad = new AjaxSunat();
    $objCodigoUnidad->ajaxPorDetraccion();
}
if (isset($_POST['porcentajeDetraccion'])) {

    $objCodigoUnidad = new AjaxSunat();
    $objCodigoUnidad->ajaxPorDetraccionCalculo();
}