<?php
session_start();

require_once "../vendor/autoload.php";
date_default_timezone_set('America/Lima');
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorEnvioSunatNotas;
use Controladores\ControladorVentas;
use Controladores\ControladorClientes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\Controlador;
use api\ApiFacturacion;
use Controladores\ControladorNotaCredito;
use Modelos\ModeloEnvioSunat;

class AjaxEvioSunat
{
    public $idVenta;
    public function ajaxActualizarEvioSunat()
    {

        $idVenta = $this->idVenta;
        $respuesta = ControladorEnvioSunat::ctrActualizarVenta($idVenta);
        echo $respuesta;
    }

    public $idComprobante;
    public function ajaxEnvioBaja()
    {

        $idComprobante = $this->idComprobante;
        $respuesta = ControladorEnvioSunat::ctrBajaComprobante($idComprobante);
        echo $respuesta;
    }   


     public $idComprobantenota;
    public function ajaxEnvioBajaNota()
    {

        $idComprobante = $this->idComprobantenota;
        $respuesta = ControladorEnvioSunatNotas::ctrBajaComprobanteNotas($idComprobante);
        echo $respuesta;
    }


    public function ajaxActualizarBaja()
    {
        if ($_POST['tipo'] == 'boleta' || $_POST['tipo'] == 'factura') {
            $tabla = 'venta';
            $item = 'id';
            $valor = $_POST['numeroComprobante'];
            $comprobantes = ControladorVentas::ctrMostrarVentasParaConsultar($tabla, $item, $valor);
            // var_dump($comprobantes);
        }
        if ($_POST['tipo'] == 'notac') {
            $tabla = 'nota_credito';
            $item = 'id';
            $valor = $_POST['numeroComprobante'];
            $comprobantes = ControladorVentas::ctrMostrarVentasParaConsultar($tabla, $item, $valor);
            // var_dump($comprobantes);
        }
        if ($_POST['tipo'] == 'notad') {
            $tabla = 'mota_debito';
            $item = 'id';
            $valor = $_POST['numeroComprobante'];
            $comprobantes = ControladorVentas::ctrMostrarVentasParaConsultar($tabla, $item, $valor);
            // var_dump($comprobantes);
        }

        $emisor = ControladorEmpresa::ctrEmisor();
        $comprobante = array(
            'tipocomp' => $comprobantes['tipocomp'],
            'serie' => $comprobantes['serie'],
            'correlativo' => $comprobantes['correlativo'],

        );
        $api = new ApiFacturacion();
        $api->consultarComprobante($emisor, $comprobante);
        if ($api->codigoMsg == '0001' && ($_POST['tipo'] == 'boleta' || $_POST['tipo'] == 'factura')) {
            if($comprobantes['feestado'] == 3 || empty($comprobantes['feestado'])){

                $idComprobante = $_POST['numeroComprobante'];
                $updateEstadoSunat = ControladorVentas::ctrActualizarVentaConsultaAceltadaSunat($idComprobante); 
            }
            
        }
        $dataConsulta = array(
            'codigoMsg' =>$api->codigoMsg,
            'msgConsulta' =>  $api->msgConsulta
        );
       echo json_encode($dataConsulta, JSON_UNESCAPED_UNICODE);
    }

    public function ajaxConsultarComprobante()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $comprobante = array(
            "tipocomp" => $_POST['tipocomprobante'],
            "serie" => $_POST['seriec'],
            "correlativo" => $_POST['correlativoc']
        );
        $api = new ApiFacturacion();
        $api->consultarComprobante($emisor, $comprobante);
        echo $api->msgConsulta;
    }

    public function ajaxConsultarTicket()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $idVenta = $_POST['idventabaja'];

        $item = 'id';
        $valor = $idVenta;
        $ventas = ControladorVentas::ctrMostrarVentas($item, $valor);

        $item = 'idenvio';
        $valor = $ventas['idbaja'];
        $bajas = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

        $ticket = $bajas['ticket'];
        $extension = pathinfo($bajas['nombrexml'], PATHINFO_EXTENSION);
        $nombrexml = basename($bajas['nombrexml'], '.' . $extension);
        $ruta_archivo_cdr = __DIR__ . "/../api/cdr/";


        $api = new ApiFacturacion();
        $api->ConsultarTicket($emisor,  $nombrexml, $ticket, $ruta_archivo_cdr);



        $codigosSunat = array(
            "feestado" => $api->codrespuesta,
            "fecodigoerror"  => $api->coderror,
            "femensajesunat"  => $api->mensajeError,
            "nombrexml"  => $api->xml,
            "xmlbase64"  => $api->xmlb64,
            "cdrbase64"  => $api->cdrb64,
            "ticket"  => $api->ticketS
        );

        if (!empty($codigosSunat['xmlbase64'])) {
            $datos = array(
                'anulado' => 's',
                'idbaja'  => $bajas['idenvio']
            );

            $actualizarVentaAnulado = ControladorEnvioSunat::ctrActualizarVentaporAnulacion($idVenta, $datos);

            $datos = array(
                'id' => $bajas['idenvio'],
                'feestado'  => 1
            );

            $actualizarresumen = ControladorEnvioSunat::ctrActualizarResumenPorBaja($datos);
        }
    }



        public function ajaxConsultarTicketNota()
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $idVenta = $_POST['idventabajaNota'];

        $item = 'id';
        $valor = $idVenta;
        $notaCredito = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);
        
        $item = 'id_nc';
        $valor = $idVenta;
        $venta = ControladorVentas::ctrMostrarVentas($item, $valor);
        $id_venta = $venta['id'];

        $item = 'idenvio';
        $valor = $notaCredito['idbaja'];
        $bajas = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

        $ticket = $bajas['ticket'];
        $extension = pathinfo($bajas['nombrexml'], PATHINFO_EXTENSION);
        $nombrexml = basename($bajas['nombrexml'], '.' . $extension);
        $ruta_archivo_cdr = __DIR__ . "/../api/cdr/";


        $api = new ApiFacturacion();
        $api->ConsultarTicket($emisor,  $nombrexml, $ticket, $ruta_archivo_cdr);



        $codigosSunat = array(
            "feestado" => $api->codrespuesta,
            "fecodigoerror"  => $api->coderror,
            "femensajesunat"  => $api->mensajeError,
            "nombrexml"  => $api->xml,
            "xmlbase64"  => $api->xmlb64,
            "cdrbase64"  => $api->cdrb64,
            "ticket"  => $api->ticketS
        );
       

        if (!empty($codigosSunat['feestado'])) {
            $datos = array(
                'anulado' => 's',
                'idbaja'  => $bajas['idenvio']
            );
            $datos_venta = array(
                'anulado' => 'n',
                'idbaja'  => null,
                'id_nc' =>null
            );

             $actualizarVentaAnulado = ControladorEnvioSunatNotas::ctrActualizarVentaporAnulacion($idVenta, $datos);
             $actualizarVentaAnulado = ModeloEnvioSunat::mdlActualizarVentaBajaNota($id_venta, $datos_venta);
            $datos = array(
                'id' => $bajas['idenvio'],
                'feestado'  => 1
            );

            $actualizarresumen = ControladorEnvioSunat::ctrActualizarResumenPorBaja($datos);
        }
    }
    public function ajaxConsultarObtenerCDR()
    {
        $idComprobante = $_POST['idComprobantecdr'];
        $item = 'id';
        $valor = $idComprobante;
        $datosComprobante = ControladorVentas::ctrMostrarVentas($item, $valor);
        $emisor = ControladorEmpresa::ctrEmisor();
        $extension = pathinfo($datosComprobante['nombrexml'], PATHINFO_EXTENSION);
        $nombrexml = basename($datosComprobante['nombrexml'], '.' . $extension);
        $ruta_archivo_cdr = __DIR__ . "/../api/cdr/";

        $comprobante = array(
            'tipoComprobante' => $datosComprobante['tipocomp'],
            'serieComprobante' => $datosComprobante['serie'],
            'numeroComprobante' => $datosComprobante['correlativo']

        );

        $api = new ApiFacturacion();
        $api->obtenerCDR($emisor,  $nombrexml, $comprobante, $ruta_archivo_cdr);



        $codigosSunat = array(
            "feestado" => $api->codrespuesta,
            "fecodigoerror"  => $api->coderror,
            "femensajesunat"  => $api->mensajeError,
            "nombrexml"  => $api->xml,
            "xmlbase64"  => $api->xmlb64,
            "cdrbase64"  => $api->cdrb64,
            "ticket"  => $api->ticketS
        );
        if (!empty($codigosSunat['xmlbase64'])) {
            $actualizarVenta = ControladorVentas::ctrActualizarVentaCDR($idComprobante, $codigosSunat);
        }
    }
    public function ajaxActualizarEvioSunatAutomatico()
    {
  
      
        $fecha_emision = date('Y-m-d');
        $item = null;
        $valor = null;
        $ventas = ControladorVentas::ctrMostrarVentasNoEnviadas($item, $valor, $fecha_emision);
       
        if($ventas){
        // $ventasS = array_values(array_unique($ventas));
        foreach($ventas as $k => $venta){
        $idVenta = $venta['id'];
        $respuesta = ControladorEnvioSunat::ctrActualizarVenta($idVenta);
        echo $respuesta;
        }
        }
    }
}
if (isset($_POST['idVenta'])) {
    $objVenta = new AjaxEvioSunat();
    $objVenta->idVenta = $_POST['idVenta'];
    $objVenta->ajaxActualizarEvioSunat();
}
if (isset($_POST['idComprobante'])) {
    $objComprobante = new AjaxEvioSunat();
    $objComprobante->idComprobante = $_POST['idComprobante'];
    $objComprobante->ajaxEnvioBaja();
}
if (isset($_POST['idComprobantenota'])) {
    $objComprobante = new AjaxEvioSunat();
    $objComprobante->idComprobantenota = $_POST['idComprobantenota'];
    $objComprobante->ajaxEnvioBajaNota();
}
if (isset($_POST['numeroComprobante'])) {
    $objbaja = new AjaxEvioSunat();
    $objbaja->ajaxActualizarBaja();
}
if (isset($_POST['tipocomprobante'])) {
    $objMostrar = new AjaxEvioSunat();
    $objMostrar->ajaxConsultarComprobante();
}
if (isset($_POST['idventabaja'])) {
    $objMostrar = new AjaxEvioSunat();
    $objMostrar->ajaxConsultarTicket();
}
if (isset($_POST['idventabajaNota'])) {
    $objMostrar = new AjaxEvioSunat();
    $objMostrar->ajaxConsultarTicketNota();
}
if (isset($_POST['idComprobantecdr'])) {
    $objMostrar = new AjaxEvioSunat();
    $objMostrar->ajaxConsultarObtenerCDR();
}
if(isset($_POST['envioAutomatico']))
{
    $objEnviar = new AjaxEvioSunat();
    $objEnviar->ajaxActualizarEvioSunatAutomatico();
}