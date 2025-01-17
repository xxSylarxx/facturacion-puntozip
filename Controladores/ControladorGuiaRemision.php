<?php

namespace Controladores;

use Modelos\ModeloGuiaRemision;
use Modelos\ModeloEnvioSunat;
use Modelos\ModeloVentas;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorSunat;
use api\GeneradorXML;
use api\ApiFacturacion;

class ControladorGuiaRemision
{

    public static $esBorrador = false;

    public static function ctrMostrar($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrar($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarDetalles($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrarDetalles($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarTraslado($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrarTraslado($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarTiposVehiculo($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrarTiposVehiculo($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarUbigeo($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarUbigeo($item, $valor);

        return $respuesta;
    }
    public static function ctrMostrarUbigeoSolo($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarUbigeoSolo($item, $valor);

        return $respuesta;
    }

    public static function ctrBuscarSerieCorrelativo($tabla, $valor, $idsucursal)
    {

        $respuesta = ModeloGuiaRemision::mdlBuscarSerieCorrelativo($tabla, $valor, $idsucursal);
        return $respuesta;
    }

    // LLENAR CARRITO DE COMPRAS
    public static function ctrLlenarCarritoGuia($carritoG)
    {
        $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida(null, null);
        foreach ($carritoG as $k => $v) {
            echo "<tr class='id-eliminar" . $k . "'>";
            $response =  "<td>" . $v['codigo'] . "</td>
                <td>" . $v['descripcion'] . "<br/>
                    <input type='text' class='datos-adicionales-item-guia input-prod' id='descripcion' name='descripcion[]' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='adicional' value='". $v['adicional'] ."' placeholder='DATOS ADICIONALES'>
                </td>
                <td><input type='text' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='servicio' value='" . $v['servicio'] . "' placeholder='Servicio'></td>
                <td><input type='text' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='caracteristica' value='" . $v['caracteristica'] . "' placeholder='Característica'></td>
                <td><input type='text' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='color' placeholder='Color' value='" . $v['color'] . "'></td>
                <td>
                    <select class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='unidad'>";
            $undHtml = '';
            foreach ($unidad_medida as $m) {
                if ($m['activo'] != 's') continue;
                $undHtml .= '<option value="' . $m['codigo'] . '" ' . ($v['unidad'] == $m['codigo'] ? 'selected' : '') . '>' . $m['descripcion'] . '</option>';
            }
            $response .= $undHtml;
            $response .=  "</select>
                </td>
                <td><input type='text' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='PO' value='" . $v['PO'] . "' placeholder='P.O'></td>
                <td><input type='text' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='partida' value='" . $v['partida'] . "' placeholder='Partida'></td>
                <td><input type='number' class='form-control input-prod' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='cantidad' placeholder='Cantidad' value='" . $v['cantidad'] . "'></td>
                <td><input type='number' class='form-control input-bultos' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='bultos' value='" . $v['bultos'] . "' placeholder='Bultos'></td>
                <td><input type='number' class='form-control input-peso' idcar='" . $k . "' cod='" . $v['codigo'] . "' campo='peso' value='" . $v['peso'] . "' placeholder='Peso'></td>
                <td><button type='button' class='btn btn-danger btn-xs btnEliminarItemCarroG' itemEliminar='" . $k . "'><i class='fas fa-trash-alt'></i></button>
                    <button type='button' class='btn btn-primary btn-xs btnAgregarSerieG' idProductoG='" . $v['id'] . "'><i class='fa fa-barcode'  data-toggle='modal' data-target='#modalEditarProductoSeries'></i></button>
                </td>";
            echo $response;
            echo  "</tr>";
        }
    }


    public static function ctrGuardarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket)
    {
        $respuesta = ModeloGuiaRemision::mdlGuardarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket);
        return $respuesta;
    }

    public static function ctrGuardarGuiaSinSunat($id_sucursal, $datosGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlGuardarGuiaSinEnviarSunat($id_sucursal, $datosGuia);
        return $respuesta;
    }

    public static function ctrActualizarGuiaSinSunat($id_sucursal, $datosGuia, $idGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlEditarGuiaSinEnviarSunat($id_sucursal, $datosGuia, $idGuia);
        return $respuesta;
    }

    public static function ctrActualizarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket, $idGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlEditarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket, $idGuia);
        return $respuesta;
    }

    public static function ctrCrearGuia($datosForm)
    {

        $emisor = ControladorEmpresa::ctrEmisor();
        $sucursal = ControladorSucursal::ctrSucursal();
        //  var_dump($emisor);
        $item = 'id';
        $valor = $datosForm['serie'];
        $seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);
        self::$esBorrador = isset($_POST['esBorrador']) ? $_POST['esBorrador'] == 'S' : false;
        if (isset($datosForm))
            $fecha = $_POST['fechaEmision'];
        $fecha2 = str_replace('/', '-', $fecha);
        $fechaEmision = date('Y-m-d', strtotime($fecha2));
        if (isset($datosForm['descripcion'])) {
            @$descItem = array_map('strtoupper', $datosForm['descripcion']);
        }
        if (isset($datosForm['idserie'])) {
            @$seriesP = $datosForm['idserie'];
        }
        $descripcion = isset($datosForm['descripcion']) ? json_encode($descItem, JSON_UNESCAPED_UNICODE) : null;
        $seriesProductos = isset($datosForm['idserie']) ? json_encode($seriesP, JSON_UNESCAPED_UNICODE) : null;
        // SERIE CORRELATIVO UPDATE - NUEVO
        $seriex['correlativo'] = $datosForm['serieCorrelativoReferencial'];
        $guia = array(
            'serie' => $seriex['serie'],
            'correlativo' => $seriex['correlativo'], // + 1 cambio
            'fechaEmision' => $fechaEmision,
            'horaEmision' => date('H:i:s'),
            'tipoDoc' => '09',
            'observacion' => isset($datosForm['comentario']) ? trim($datosForm['comentario']) : '',
            'descripcion' => $descripcion,
            'series' => $seriesProductos
        );
        // var_dump($guia);
        // return false;
        $docbaja = array(
            'nroDoc' => '',
            'tipoDoc' => ''
        );
        $tipocomp = substr($datosForm['serieCorrelativoReferencial'], 0, 1);
        $seriecomp = '';
        if ($tipocomp == 'F') {
            $seriecomp = '01';
        }
        if ($tipocomp == 'B') {
            $seriecomp = '03';
        }
        $relDoc = array(
            'nroDoc' => $datosForm['serieCorrelativoReferencial'],
            'tipoDoc' => $seriecomp
        );


        $remitente = array(
            'ruc' => $emisor['ruc'],
            'razonsocial' => $emisor['razon_social'],

        );

        $destinatario = array(
            'tipoDoc' => isset($datosForm['tipoDoc']) ? $datosForm['tipoDoc'] : '6',
            'numDoc' => $datosForm['docIdentidad'],
            'nombreRazon' => $datosForm['razon_social']

        );
        $terceros = array(
            'tipoDoc' => '',
            'numDoc' => '',
            'nombreRazon' => ''
        );

        $fecha = $_POST['fechaInicialTraslado'];
        $fecha2 = str_replace('/', '-', $fecha);
        $fechaTraslado = date('Y-m-d', strtotime($fecha2));
        $datosEnvio = array(
            'codTraslado' => $datosForm['motivoTraslado'],
            'descTraslado' => 'VENTA',
            'uniPesoTotal' => 'KGM',
            'pesoTotal' => $datosForm['pesoBruto'],
            'numBultos' => $datosForm['numeroBultos'],
            'indTransbordo' => 'false',
            'modTraslado' => $datosForm['modalidadTraslado'],
            'fechaTraslado' => $fechaTraslado,
            'tipoVehiculo' => isset($datosForm['tipoVehiculo']) ? $datosForm['tipoVehiculo'] : ''

        );
        if ($datosForm['modalidadTraslado'] == '02') {
            $transportista = array(
                'tipoDoc' =>  $datosForm['tipoDocTransporte'],
                'numDoc' => $datosForm['docTransporte'],
                'nombreRazon' => $datosForm['nombreRazon'],
                'apellidosRazon' => $datosForm['apellidosRazon'],
                'placa' => strtoupper($datosForm['placa']),
                'tipoDocChofer' => $datosForm['tipoDocTransporte'],
                'numDocChofer' => $datosForm['docTransporte'],
                'numBreveteChofer' => strtoupper($datosForm['numBrevete']),
            );
        } else {
            $transportista = array(
                'tipoDoc' =>  $datosForm['tipoDocTransporte'],
                'numDoc' => $datosForm['docTransporte'],
                'nombreRazon' => $datosForm['nombreRazon'],
                'apellidosRazon' => '',
                'placa' => '',
                'tipoDocChofer' => $datosForm['tipoDocTransporte'],
                'numDocChofer' => $datosForm['docTransporte'],
                'numBreveteChofer' => ''
            );
        }

        $partida = array(
            'ubigeo' => $datosForm['ubigeoPartida'],
            'direccion' => strtoupper($datosForm['direccionPartida'])
        );
        $llegada = array(
            'ubigeo' => $datosForm['ubigeoLlegada'],
            'direccion' => strtoupper($datosForm['direccionLlegada'])
        );

        $contenedor = array(
            'numContenedor' => isset($datosForm['numeroContenedor']) ? $datosForm['numeroContenedor'] : ''
        );

        $puerto = array(
            'codPuerto' => isset($datosForm['codigoPuerto']) ? $datosForm['codigoPuerto'] : ''
        );

        $SERIE_SIGUIENTE = $datosForm['serieCorrelativoReferencial'];

        $datosGuia = array(
            'guia' => $guia,
            'docBaja' =>  $docbaja,
            'relDoc' => $relDoc,
            'remitente' => $remitente,
            'destinatario' => $destinatario,
            'terceros' => $terceros,
            'datosEnvio' => $datosEnvio,
            'transportista' => $transportista,
            'llegada' => $llegada,
            'contenedor' => $contenedor,
            'partida' => $partida,
            'puerto' => $puerto,
            'comp_ref'   => null,
            'id_cliente' => $datosForm['idCliente'],
            'id_conductor' => $datosForm['listConductores'],
            'borrador'   => $datosForm['envioSunat'] == 'enviar' ? 'S' : 'N'
        );
        if (!isset($_SESSION['carritoG'])) {
            $_SESSION['carritoG'] = array();
        }
        $carritoG = $_SESSION['carritoG'];
        //extract($_REQUEST);
        $detalle = array();
        $carritoG = array_values($carritoG);

        foreach ($carritoG as $k => $v) {
            $itemx = array(
                'index' => ++$k,
                'unidad' => $v['unidad'],
                'cantidad' => $v['cantidad'],
                'descripcion' => $v['descripcion'],
                'codigo' => $v['codigo'],
                'codProdSunat' => '',
                'id_producto' => $v['id'],
                'id' => $v['id'],
                'peso'    => isset($v['peso']) ? $v['peso'] : 0,
                'bultos'  => isset($v['bultos']) ? $v['bultos'] : 0,
                'PO'      => isset($v['PO']) ? $v['PO'] : 0,
                'color'   => isset($v['color']) ? $v['color'] : 0,
                'partida' => isset($v['partida']) ? $v['partida'] : 0,
                'adicional' => isset($v['adicional']) ? $v['adicional'] : '',
                'servicio'  => isset($v['servicio']) ? $v['servicio'] : '',
                'caracteristica' => isset($v['caracteristica']) ? $v['caracteristica'] : ''
            );
            $itemx;
            $detalle[] = $itemx;
        }

        $nombre = $emisor['ruc'] . '-' . $seriex['tipocomp'] . '-' . $seriex['serie'] . '-' . $guia['correlativo'];

        $comprobante = array(
            'serie' => $seriex['serie'],
            'correlativo'  => $guia['correlativo'],
            'codvendedor' => $_SESSION['id']
        );

        // RUTAS DE CDR Y XML 
        $ruta_archivo_xml = "../api/xml/";
        $ruta_archivo_cdr = "../api/cdr/";
        $ruta = "../api/xml/";
        if (
            !empty($datosForm['idCliente']) && !empty($datosForm['docIdentidad']) && !empty($datosForm['razon_social'])
            && !empty($datosForm['fechaInicialTraslado']) && !empty($datosForm['pesoBruto']) && !empty($datosForm['numeroBultos'])   && !empty($datosForm['direccionPartida']) && !empty($datosForm['ubigeoPartida']) && !empty($datosForm['direccionLlegada']) && !empty($datosForm['ubigeoLlegada'])
        ) {


            // if (($datosForm['modalidadTraslado'] == '02' && !empty($datosForm['placa'])  && !empty($datosForm['numBrevete'])) || ($datosForm['modalidadTraslado'] == '01' && empty($datosForm['placa']))) {
            if (!empty($detalle)) {
                if ($datosForm['envioSunat'] != 'no') {
                    if ($datosForm['envioSunat'] == 'enviar') {
                        $generadoXML = new GeneradorXML();
                        $generadoXML->CrearXMLGuiaRemision($ruta . $nombre, $emisor, $datosGuia, $detalle);
                        $api = new ApiFacturacion();
                        $api->EnviarGuiaRemision($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr, "../");
                        $token = $api->token;
                        $ticket = $api->ticketS;
                        $nombre_archivo = $nombre . '.zip';
                        //CONSULTAR TICKET=============================
                        $obtenerCdr = new ApiFacturacion();
                        $obtenerCdr->ConsultarTicketGuiaRemision($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr);
                        if (!empty($obtenerCdr)) {
                            $codigosSunat = array(
                                "feestado" => $obtenerCdr->codrespuesta,
                                "fecodigoerror"  => $obtenerCdr->coderror,
                                "femensajesunat"  => $obtenerCdr->mensajeError,
                                "nombrexml"  => $api->xml,
                                "xmlbase64"  => $obtenerCdr->xmlb64,
                                "cdrbase64"  => $obtenerCdr->cdrb64,
                            );
                        } else {
                            $codigosSunat = array(
                                "feestado" => 3,
                                "fecodigoerror"  => '',
                                "femensajesunat"  => '',
                                "nombrexml"  => $api->xml,
                                "xmlbase64"  => "R-" . $nombre . '.xml',
                                "cdrbase64"  => "R-" . $nombre . '.zip',
                            );
                        }
                    }
                    $datos = array(
                        'id' => $datosForm['serie'],
                        'correlativo' => $datosGuia['guia']['correlativo'],
                    );
                    $id_sucursal = $datosForm['idSucursal'];
                    if (self::$esBorrador) {
                        $guardarGuia = ControladorGuiaRemision::ctrActualizarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket, $_POST['guiaEditar']);
                    } else {
                        $guardarGuia = ControladorGuiaRemision::ctrGuardarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket);
                        $actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
                    }
                } else {
                    $generadoXML = new GeneradorXML();
                    $generadoXML->CrearXMLGuiaRemision($ruta . $nombre, $emisor, $datosGuia, $detalle);
                    $datos = array(
                        'id' => $datosForm['serie'],
                        'correlativo' => $datosGuia['guia']['correlativo'],
                    );
                    $id_sucursal = $datosForm['idSucursal'];
                    $datosGuia['nombrexml'] = $nombre . '.xml';
                    if (self::$esBorrador) {
                        $guardarGuia = ControladorGuiaRemision::ctrActualizarGuiaSinSunat($id_sucursal, $datosGuia, $_POST['guiaEditar']);
                    } else {
                        $actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
                        $guardarGuia = ControladorGuiaRemision::ctrGuardarGuiaSinSunat($id_sucursal, $datosGuia);
                    }
                }
                $guiaid = ModeloGuiaRemision::mdlObtenerUltimoComprobanteIdGuia();
                $idGuia = $guiaid['id'];
                if (self::$esBorrador) {
                    ModeloGuiaRemision::mdlEliminarGuiaDetalle($idGuia);
                }
                ModeloGuiaRemision::mdlInsertarDetallesGuia($idGuia, $detalle);
                if ($guardarGuia == 'ok') {
                    $valor = null;
                    /* $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor); */
                    if (empty($datosForm['serieCorrelativoReferencial'])) {
                        //INVENTARIO====================================================
                        $id_sucursal = $sucursal['id'];
                        $entradasInventario = ControladorInventarios::ctrNuevaSalidaGuia($detalle, $comprobante, $id_sucursal);
                    }
                    echo "
                       <div class='contenedor-print'>
                      <form id='printC' name='printC' method='post' action='vistas/print/printguia/' target='_blank'>
                     <input type='radio' id='a4' name='a4' value='A4'>
                     <input type='radio' id='tk' name='a4' value='TK'>
                     <input type='hidden' id='idCo' name='idCo' value='" . $idGuia . "'>
                      <button  id='printA4' ></button>
                      </form></div>";
                    echo "<script>
                      
                          $('#formGuia').each(function(){
                            this.reset();	
                            $('.nuevoProducto table #itemsPG').html('');
                        })
                            $('.eliminar_item').remove();
                        </script>";
                    unset($_SESSION['carritoG']);
                }
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'SE NECESITA PRODUCTOS O SERVICIOS',
                            text: '',
                            html: `Debes ingresar productos o servicios que se encuentren o van a ir en la FACTURA o BOLETA`
                        })
                            </script>";
            }
            // } else {
            //     echo "<script>
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'CAMPOS OBLIGATORIOS',
            //                 text: 'LLENE TODOS LOS CAMPOS OBLIGATORIOS',
            //                 html: `Debes ingresar todos los campos requeridos (<span style='color:red; font-size: 18px;'>*</span>)`
            //             })
            //                 </script>";
            // }
        } else {

            echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'CAMPOS OBLIGATORIOS',
                            text: '',
                            html: `Debes ingresar todos los campos requeridos (<span style='color:red; font-size: 18px;'>*</span>)`
                        })
                            </script>";
        }
    }
    // MOSTRAR VENTAS DETALLES PRODUCTOS
    public static function ctrMostrarDetallesProductosGuia($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarDetallesProductosGuia($item, $valor);
        return $respuesta;
    }

    public function ctrListarGuias()
    {
        $respuesta = ModeloGuiaRemision::mdlListarGuias();
        echo $respuesta;
    }

    public function ctrListarGuiasRetorno()
    {
        $respuesta = ModeloGuiaRemision::mdlListarGuiasRetorno();
        echo $respuesta;
    }
    public static function ctrDesactivarRetorno($datos)
    {
        $tabla = 'guia';
        $respuesta = ModeloGuiaRemision::mdlDesactivarRetorno($tabla, $datos);
        return $respuesta;
    }

    public static function ctrActualizarCDR($idGuia, $codigosSunat)
    {

        $respuesta = ModeloGuiaRemision::mdlActualizarcdr($idGuia, $codigosSunat);
        return $respuesta;
    }

    public static function ctrObtenerGuia($idGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrar('guia', 'id', $idGuia);
        return $respuesta;
    }

    public static function ctrObtenerGuiaById($idGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlBuscar($idGuia);
        return $respuesta;
    }

    public static function ctrObtenerGuiaDetalleById($idGuia)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrarDetalles('guia_detalle', 'id_guia', $idGuia);
        return $respuesta;
    }

    public static function ctrLlenarGuiaRemisionDetalle($sucursal, $detalle)
    {
        $_SESSION['carritoG'] = array();
        if (!empty($detalle)) {
            $carritoG = $_SESSION['carritoG'];
            $item = 'id';
            foreach ($detalle as $key => $value) {
                $item = $key;
                $producto = ControladorProductos::ctrMostrarProductos('id', $value['id_producto'], $sucursal);
                if ($producto) {
                    $carritoG[$item] = array(
                        'id' => $value['id_producto'],
                        'codigo' => $producto['codigo'],
                        'descripcion' => $producto['descripcion'],
                        'unidad' => $producto['codunidad'],
                        'cantidad' => $value['cantidad'],
                        'peso' => $value['peso'],
                        'bultos' => $value['bultos'],
                        'color' => $value['color'],
                        'PO' => $value['PO'],
                        'partida' => $value['partida'],
                        'adicional' => $value['adicional'],
                        'servicio' => $value['servicio'],
                        'caracteristica' => $value['caracteristica']
                    );
                }
            }
            $_SESSION['carritoG'] = $carritoG;
        }
    }

    public static function ctrEliminarGuia($id) {
        ModeloGuiaRemision::mdlEliminarGuiaDetalle($id);
        ModeloGuiaRemision::mdlEliminarGuia($id);
        return 'ok';
    }

    public static function ctrAnularGuia($id) {
        ModeloGuiaRemision::mdlAnularGuia($id);
        return 'ok';
    }

    public static function ctrActualizarEstado($id) {
        ModeloGuiaRemision::mdlActualizarGuiaEstado($id, 2);
    }
}
