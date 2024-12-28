<?php

namespace Controladores;

use Modelos\ModeloEnvioSunatNotas;
use Modelos\ModeloEnvioSunat;
use Modelos\ModeloVentas;
use Modelos\ModeloNotaCredito;
use Controladores\ControladorEmpresa;
use Controladores\ControladorNotaCredito;
use Controladores\ControladorProductos;
use Controladores\ControladorInventarios;
use Controladores\ControladorSucursal;
use api\GeneradorXML;
use api\ApiFacturacion;

require_once "cantidad_en_letras.php";

class ControladorEnvioSunatNotas
{

  
    public static function ctrBajaComprobanteNotas($idComprobante)
    {
        $emisor = ControladorEmpresa::ctrEmisor();
        $sucursal = ControladorSucursal::ctrSucursal();

        
        $item = 'id';
        $valor = $idComprobante;
        $ventas = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);

        $item = 'idventa';
        $valor = $ventas['id'];
        $bajas = ControladorEnvioSunat::ctrMostrarDetallesResumenes($item, $valor);
        // var_dump($bajas);
        if (!empty($bajas)) {

            echo "<br/><button class='btn  bajadacompNota' idcomp=" . $idComprobante . ">OBTENER CDR</button>";
        } else {




            $item = "id";
            $valor = $idComprobante;
            $venta = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);
            

            $serie = date('Ymd');
            $item = 'tipocomp';
            $valor = 'RA';
            $fila_serie = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

            $correlativo = 1;
            if ($fila_serie['serie'] != $serie) {
                $datos = array(
                    'id' => $fila_serie['id'],
                    'serie' => $serie,
                );
                $objCompartido = ControladorSunat::ctrActualizarSerieResumen($datos);
            } else {
                $correlativo = $fila_serie['correlativo'] + 1;
            }


            $cabecera = array(
                "tipodoc"        => "RA",
                "serie"            => $serie,
                "correlativo"    => $correlativo,
                "fecha_emision" => $venta['fecha_emision'],
                "fecha_envio"    => date('Y-m-d'),
                'id_sucursal'    => $sucursal['id'],
                'resumen' => 0,
                'baja' => 3,
            );


            $items = array();

            $id = $idComprobante;
            $factura1 = ModeloEnvioSunatNotas::mdlObtenerComprobantesId($id);
            $i = 1;
            foreach ($factura1 as $k => $factura) {

                $items[] = array(
                    "item"                => $i,
                    "idventa"           => $factura['id'],
                    "tipodoc"            => $factura["tipocomp"],
                    "serie"                => $factura["serie"],
                    "correlativo"        => $factura["correlativo"],
                    "motivo"            => "ERROR EN DOCUMENTO",
                    'resumen'           => '',
                    'baja'              => 3,
                );
                $i++;
            }
            // RUTAS DE CDR Y XML 
            $ruta_archivo_xml = "../api/xml/";
            $ruta_archivo_cdr = "../api/cdr/";
            $ruta = "../api/xml/";

            $generadoXML = new GeneradorXML();
            $api = new ApiFacturacion();

            $nombrexml = $emisor['ruc'] . '-' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];

            $generadoXML->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $ruta . $nombrexml);

            $ticket = $api->EnviarResumenComprobantes($emisor, $nombrexml, $ruta_archivo_xml, "../");

            $datos_comprobante = array(
                'codigocomprobante' => $cabecera['tipodoc'],
                'serie'     => $cabecera['serie'],
                'correlativo' => $cabecera['correlativo']
            );
            // $api->ConsultarTicket($emisor,  $nombrexml, $ticket, $ruta_archivo_cdr);

            if (strlen($ticket) > 6) {

                $codigosSunat = array(
                    "feestado" => '3',
                    "fecodigoerror"  => '',
                    "femensajesunat"  => '',
                    "nombrexml"  => $nombrexml . '.XML',
                    "xmlbase64"  => '',
                    "cdrbase64"  => '',
                    "ticket"  => $ticket
                );



                $datos = array(
                    'id' => $fila_serie['id'],
                    'correlativo'     => $correlativo,
                );
                $objCompartido = ControladorSunat::ctrActualizarCorrelativo($datos);



                $insertarResumenBaja = ModeloEnvioSunatNotas::mdlInsertarResumenBaja($cabecera, $codigosSunat);

                $obtenerUltimoId = ModeloEnvioSunatNotas::mdlObtenerUltimoResumenBajaId();

                $idEnvioBaja = $obtenerUltimoId['idenvio'];

                $insertarDetallesResumenBaja = ModeloEnvioSunatNotas::mdlInsertarDetallesResumenBaja($idEnvioBaja, $items);

                $idVenta = $idComprobante;
                $datos = array(
                    'anulado' => 'n',
                    'idbaja'  => $idEnvioBaja
                );
                $actualizarNotaAnulado = ModeloEnvioSunatNotas::mdlActualizarNotaCreditoBaja($idVenta, $datos);
               

                $item = 'idnc';
                $valor = $idVenta;
                $respuestaDetalles = ControladorNotaCredito::ctrMostrarDetallesNc($item, $valor);
                $detalle = array();
                foreach ($respuestaDetalles as $k => $value) {
                    $detalle[] = array(
                        'id' => $value['idproducto'],
                        'cantidad'     => $value['cantidad']
                    );
                }
                $valor = $idVenta;
                $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);


                $comprobante =  array(
                    "serie"                => $factura["serie"],
                    "correlativo"        => $factura["correlativo"],
                    "codvendedor"        => $_SESSION['id'],
                );
                $id_sucursal = $sucursal['id'];
                $inventario = ControladorInventarios::ctrNuevaDevolucionVenta($detalle, $comprobante, $id_sucursal);

                echo "<br/><button class='btn  bajadacompNota' idcomp=" . $idComprobante . ">OBTENER CDR</button>";
            }
        }
    }
    public static function ctrMostrarBajas($item, $valor)
    {
        $tabla = 'envio_resumen';
        $respuesta = ModeloEnvioSunatNotas::mdlMostrarBajas($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarBaja($item, $valor)
    {
        $tabla = 'envio_resumen';
        $respuesta = ModeloEnvioSunatNotas::mdlMostrarBaja($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrActualizarBajaFactura($datos)
    {

        $respuesta = ModeloEnvioSunatNotas::mdlActualizarBajaFactura($datos);
        return $respuesta;
    }
    public static function ctrActualizarVentaporAnulacion($idVenta, $datos)
    {

        $respuesta = ModeloEnvioSunatNotas::mdlActualizarNotaCreditoBaja($idVenta, $datos);
        return $respuesta;
    }
    public static function ctrActualizarResumenPorBaja($datos)
    {

        $respuesta = ModeloEnvioSunatNotas::mdlActualizarResumenPorBaja($datos);
        return $respuesta;
    }
    public static function ctrMostrarDetallesResumenes($item, $valor)
    {
        $tabla = 'envio_resumen_detalle';
        $respuesta = ModeloEnvioSunatNotas::mdlMostrarDetallesResumenes($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrElimiarElementXML($ruta_archivo_xml, $nombre)
    {
        $doc = new \DOMDocument();
        $doc->load($ruta_archivo_xml . $nombre . '.XML');
        $hash = $doc->getElementsByTagName('Signature');
        foreach ($hash as $rama) {

            $rama->parentNode->removeChild($rama);
        }
        $doc->save($ruta_archivo_xml . $nombre . '.XML');
    }

    
}
