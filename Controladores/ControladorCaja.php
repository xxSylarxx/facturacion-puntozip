<?php

namespace Controladores;

use Modelos\ModeloCaja;

date_default_timezone_set('America/Lima');

class ControladorCaja
{

    public static function ctrMostrarCajas($item, $valor)
    {

        $tabla = 'cajas';

        $respuesta = ModeloCaja::mdlMostrarCajas($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrCrearCaja()
    {
        if (empty($_POST['nuevonombre']) || empty($_POST['nuevonumero'])) {
            echo "LLENE TODOS LOS CAMPOS";
            exit;
        }
        $tabla = 'cajas';
        $datos = array(
            'nombre' => strtoupper($_POST['nuevonombre']),
            'numero_caja' => $_POST['nuevonumero'],
        );
        $respuesta = ModeloCaja::mdlCrearCaja($tabla, $datos);

        return $respuesta;
    }
    public static function ctrEditarCaja()
    {
        if (empty($_POST['editarnombre']) || empty($_POST['editarnumero'])) {
            echo "LLENE TODOS LOS CAMPOS";
            exit;
        }
        $tabla = 'cajas';
        $fechamodifica = date("Y-m-d H:i:s");
        $datos = array(
            'id' => $_POST['idCajae'],
            'nombre' => strtoupper($_POST['editarnombre']),
            'numero_caja' => $_POST['editarnumero'],
            'activo' => $_POST['cajaactiva'],
            'fecha_modifica' => $fechamodifica,
        );
        $respuesta = ModeloCaja::mdlEditarCaja($tabla, $datos);

        return $respuesta;
    }

    //ARQUEO DE CAJAS=====================================================
    public static function ctrMostrarArqueoCajas($item, $valor)
    {

        $tabla = 'arqueo_cajas';

        $respuesta = ModeloCaja::mdlMostrarCajas($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarArqueoCajasid($item, $valor)
    {

        $tabla = 'arqueo_cajas';

        $respuesta = ModeloCaja::mdlMostrarArqueoCajas($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrAperturaCaja()
    {
        session_start();
        if (empty($_POST['cajaid'])) {
            echo "DEBE ESCOGER UNA CAJA";
            exit;
        }
        if (empty($_POST['montoapertura'])) {
            echo "INGRESE UN MONTO DE APERTURA";
            exit;
        }
        $tabla = 'arqueo_cajas';
        $fechaapertura = date("Y-m-d H:i:s");
        $datos = array(
            'id_caja' => $_POST['cajaid'],
            'id_usuario' => $_SESSION['id'],
            'monto_inicial' => $_POST['montoapertura'],
            'fecha_apertura' => $fechaapertura,
        );
        $respuesta = ModeloCaja::mdlAperturaCaja($tabla, $datos);

        return $respuesta;
    }

    public static function ctrCierreCaja()
    {
        session_start();
        if (empty($_POST['idCajaCierre'])) {
            echo "ERROR EN CIERRE";
            exit;
        }

        $tabla = 'arqueo_cajas';
        $fechacierre = date("Y-m-d H:i:s");
        $datos = array(
            'id' => $_POST['idCajaCierre'],
            'monto_final' => $_POST['monto_final'],
            'fecha_cierre' => $fechacierre,
            'total_ventas' => $_POST['total_ventas'],
            'egresos' => $_POST['egresos'],
            'gastos' => $_POST['gastos'],
            'estado' => 0,
        );
        $respuesta = ModeloCaja::mdlCierreCaja($tabla, $datos);

$id_usuario = $_POST['idusuariocaja'];
        if ($respuesta == 'ok') {
            $tabla = 'venta';
            $updatev = ModeloCaja::mdlActualizarVentaCierre($tabla, $id_usuario);
            $tabla = 'compra';
            $updatec = ModeloCaja::mdlActualizarVentaCierre($tabla, $id_usuario);
            $tabla = 'gastos';
            $updatega = ModeloCaja::mdlActualizarGastoCierre($tabla, $id_usuario);
            $tabla = 'nota_credito';
            $updatenc = ModeloCaja::mdlActualizarNotasCierre($tabla, $id_usuario);
            $tabla = 'nota_debito';
            $updatend = ModeloCaja::mdlActualizarNotasCierre($tabla, $id_usuario);
        }
        return $respuesta;
    }
    public  function ctrListarArqueoCajas()
    {

        $content = ModeloCaja::mdlListarArqueoCajas();
        echo $content;
    }

    public static function ctrTotalVentas($where)
    {
        $tabla = 'venta';
        $respuesta = ModeloCaja::mdlTotalVentas($tabla, $where);
        return $respuesta;
    }

    public static function ctrTotalRegistrosVentas($where)
    {
        $tabla = 'venta';
        $respuesta = ModeloCaja::mdlTotalRegistrosVentas($tabla, $where);
        return $respuesta;
    }
    public static function ctrTotalNotas($tabla, $where)
    {

        $respuesta = ModeloCaja::mdlTotalNotas($tabla, $where);
        return $respuesta;
    }


    public static function ctrTotalCompras($where)
    {
        $tabla = 'compra';
        $respuesta = ModeloCaja::mdlTotalCompras($tabla, $where);
        return $respuesta;
    }
    public static function ctrTotalGastos($where)
    {
        $tabla = 'gastos';
        $respuesta = ModeloCaja::mdlTotalGastos($tabla, $where);
        return $respuesta;
    }

    // public static function ctrActualizarVentaCierre($tabla, $datos)
    // {
    //     $respuesta = ModeloCaja::mdlActualizarVentaCierre($tabla, $datos);
    //     return $respuesta;
    // }
}
