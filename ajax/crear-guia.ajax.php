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
use Modelos\ModeloGastos;
use Modelos\ModeloGuiaRemision;

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
        //Asignamos a la variable $carro los valores guardados en la sessi贸n
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

    public function ajaxActualizarCarroGuia()
    {
        if (isset($_POST['idProducto_update'])) {
            $idCar = $_POST['idCar'];
            $idProducto = $_POST['idProducto_update'];
            $campo = $_POST['campo'];
            $carritoG = $_SESSION['carritoG'];
            $item = $idCar;
            if (is_numeric($idCar) && isset($carritoG[$item])) {
                $carritoG[$item][$campo] = $_POST['valor'];
                $_SESSION['carritoG'] = $carritoG;
                echo 'OK';
            }
        } else {
            echo 'ERROR';
        }
        die();
    }

    public function ajaxLlenaCarroGuia()
    {
        $series = '';
        if (isset($_POST['serieG'])) {
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
            /* foreach ($carritoG as $k => $v) {
                if ($v['id'] == $producto['id']) {
                    $item = $k;
                    $existe = true;
                    break;
                }
            } */

            $carritoG[$item] = array(
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'descripcion' => $producto['descripcion'],
                'unidad' => $producto['codunidad'],
                'cantidad' => $cantidad,
                'peso' => '0',
                'bultos' => '0',
                'color' => '',
                'PO' => '',
                'partida' => '',
                'adicional' => '',
                'servicio' => '',
                'caracteristica' => $producto['caracteristica']
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
        //Asignamos a la variable $carro los valores guardados en la sessi贸n
        unset($carritoG[$idEliminarCarro]);
        $carritoG = array_values($carritoG);
        $_SESSION['carritoG'] = $carritoG;
        $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
    }

    public function ajaxCrearGuia()
    {
        // $datosForm = $_POST;
        // // var_dump($datosForm);
        // // exit();
        // $respuesta =  ControladorGuiaRemision::ctrCrearGuia($datosForm);
        // return $respuesta;

        try {
            if (!isset($_SESSION['carritoG']) || empty($_SESSION['carritoG'])) {
                throw new Exception("No hay productos en el carrito");
            }

            foreach ($_SESSION['carritoG'] as $key => $producto) {
                $existeProducto = ControladorProductos::ctrMostrarProductos('codigo', $producto['codigo'], $_POST['idSucursal']);

                // echo '<script>
                //     alert("existeProducto: ' . json_encode($_SESSION['id_guia_integracion'] ?? "no entra") . '");
                // </script>';

                if (!$existeProducto) {
                    // Crear nuevo producto
                    $datosProducto = array(
                        'id_categoria' => null,
                        'codigo' => $producto['codigo'],
                        'descripcion' => $producto['descripcion'],
                        'unidad' => $producto['unidad'] ?? 'NIU',
                        'stock' => $producto['cantidad'] ?? 0,
                        'caracteristica' => $producto['caracteristica'] ?? '',
                        'tipo_precio' => '01',
                        'id_sucursal' => $_POST['idSucursal']
                    );

                    $resultado = ControladorProductos::ctrCrearProductoIntegración($datosProducto);

                    if ($resultado) {
                        $nuevoProducto = ControladorProductos::ctrMostrarProductos('codigo', $producto['codigo'], $_POST['idSucursal']);
                        if ($nuevoProducto) {
                            $_SESSION['carritoG'][$key]['id'] = $nuevoProducto['id'];
                        }
                    }
                } else {
                    $_SESSION['carritoG'][$key]['id'] = $existeProducto['id'];
                }
            }


            $datosForm = $_POST;

            // guardamos id guia integracion
            if (isset($_SESSION['id_guia_integracion'])) {
                $datosForm['id_guia_integracion'] = $_SESSION['id_guia_integracion'];
            }

            $respuesta = ControladorGuiaRemision::ctrCrearGuia($datosForm);

            // Limpiar el ID de integración
            if (isset($_SESSION['id_guia_integracion'])) {
                unset($_SESSION['id_guia_integracion']);
            }

            echo $respuesta;
        } catch (Exception $e) {
            echo $e->getMessage();

            // throw new Exception("Error al crear la guía");
        }
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
        $desactivarRetornoGuia = ControladorGuiaRemision::ctrDesactivarRetorno($datos);
    }

    public function comprobarMayorDe48Horas($fecha_emision, $hora)
    {
        $fechaHora = $fecha_emision . ' ' . $hora;
        $fechaHoraDateTime = new DateTime($fechaHora);
        $fechaActual = new DateTime();
        $intervalo = $fechaActual->diff($fechaHoraDateTime);
        $horasDeDiferencia = ($intervalo->days * 24) + $intervalo->h;
        return $horasDeDiferencia >= 48 ? true : false;
    }

    public function ajaxGetCDR()
    {
        $idGuia = $_POST['idgetGuia'];
        $tabla = 'guia';
        $item = 'id';
        $valor = $_POST['idgetGuia'];
        $guia = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);
        // VERIFICAR SI PASO MAS DE 48 HORAS
        $mayor_48_horas = $this->comprobarMayorDe48Horas($guia['fecha_emision'], $guia['hora']);

        if ($mayor_48_horas) {
            ControladorGuiaRemision::ctrActualizarEstado($idGuia);
            return 'GUIA RECHAZADA';
        }

        $emisor = ControladorEmpresa::ctrEmisor();

        $token_result = ApiFacturacion::ObtenerToken($emisor);
        // Verificar si la respuesta es un objeto y si tiene la propiedad 'access_token'
        if (is_object($token_result) && isset($token_result->access_token)) {
            $token = $token_result->access_token;

            // si existe $$token_result se gnera el xml


        } else {
            // Manejar el error
            error_log("Error al obtener el token de acceso: " . print_r($token_result, true));
            // Mostrar un mensaje de error al usuario o tomar alguna otra acción
            echo "Ocurrió un error al obtener el token de acceso. Por favor, inténtalo nuevamente más tarde.";
        }
        // die('No existe token'); exit();

        $ruta_archivo_xml = "../api/xml/";
        $ruta_archivo_cdr = "../api/cdr/";
        $nombre = $emisor['ruc'] . '-' . $guia['tipodoc'] . '-' . $guia['serie'] . '-' . $guia['correlativo'];
        $nombreXML = '';
        if ($guia['borrador'] == 'S') {
            $api = new ApiFacturacion();
            $api->EnviarGuiaRemision($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr, "../");
            $token = $api->token;
            $ticket = $api->ticketS;
            $nombre_archivo = $nombre . '.zip';
            if ($ticket) {
                ModeloGuiaRemision::mdlActualizarGuiaTicket($valor, $ticket);
                ModeloGuiaRemision::mdlActualizarGuiaEstado($valor, ['feestado' => 1]);
            }
            $nombreXML = $api->xml;
        } else {
            $ticket = $guia['ticket'];
            $nombreXML = isset($guia['nombrexml']) ? $guia['nombrexml'] : '';
            $nombre_archivo = $nombre . '.zip';
            if (!empty($ticket)) {
                ModeloGuiaRemision::mdlActualizarGuiaEstado($valor, ['feestado' => 1]);
            }
        }
        $obtenerCdr = new ApiFacturacion();
        $obtenerCdr->ConsultarTicketGuiaRemision($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr, $valor);
        if (!empty($obtenerCdr)) {
            $codigosSunat = array(
                "feestado" => $obtenerCdr->codrespuesta,
                "fecodigoerror"  => $obtenerCdr->coderror,
                "femensajesunat"  => $obtenerCdr->mensajeError,
                "xmlbase64"  => $obtenerCdr->xmlb64,
                "cdrbase64"  => $obtenerCdr->cdrb64,
            );
            if (isset($codigosSunat['feestado'])) {
                ControladorGuiaRemision::ctrActualizarCDR($idGuia, $codigosSunat);

                // en caso que el estado feestado sea 1, se ejecua una api

            }
            ModeloGuiaRemision::mdlActualizarCDRName($idGuia, [
                'xmlbase64' => $codigosSunat['xmlbase64'],
                'cdrbase64' => $codigosSunat['cdrbase64']
            ]);
        } else {
            $codigosSunat = array(
                "feestado" => 3,
                "fecodigoerror"  => '',
                "femensajesunat"  => '',
                "nombrexml"  => $nombreXML,
                "xmlbase64"  => "R-" . $nombre . '.xml',
                "cdrbase64"  => "R-" . $nombre . '.zip',
            );
            if (isset($codigosSunat['feestado'])) {
                ControladorGuiaRemision::ctrActualizarCDR($idGuia, $codigosSunat);
            }
            ModeloGuiaRemision::mdlActualizarCDRName($idGuia, [
                'xmlbase64' => $codigosSunat['xmlbase64'],
                'cdrbase64' => $codigosSunat['cdrbase64']
            ]);
        }
    }

    public function ajaxEliminarGuia()
    {
        $idGuiaEliminar = $_POST['idGuiaDelete'];
        $respuesta = ControladorGuiaRemision::ctrEliminarGuia($idGuiaEliminar);
        echo $respuesta;
    }

    public function ajaxAnularGuia()
    {
        $idGuiaAnular = $_POST['idGuiaAnular'];
        $respuesta = ControladorGuiaRemision::ctrAnularGuia($idGuiaAnular);
        echo $respuesta;
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
if (isset($_POST['idProducto_update'])) {
    $objSerieNota = new AjaxGuia();
    $objSerieNota->ajaxActualizarCarroGuia();
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

if (isset($_POST['idGuiaDelete'])) {
    $objretornar = new AjaxGuia();
    $objretornar->ajaxEliminarGuia();
}

if (isset($_POST['idGuiaAnular'])) {
    $objretornar = new AjaxGuia();
    $objretornar->ajaxAnularGuia();
}
