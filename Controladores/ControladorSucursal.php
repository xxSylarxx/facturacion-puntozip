<?php

namespace Controladores;

use Modelos\ModeloSucursal;
use api\ApiFacturacion;

class ControladorSucursal
{


    public static function ctrSucursalPrincipal($item, $valor)
    {
        $tabla = "sucursales";

        $respuesta = ModeloSucursal::mdlMostrarSucursal($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrMostrarSucursalTotal($item, $valor)
    {
        $tabla = "sucursales";

        $respuesta = ModeloSucursal::mdlMostrarSucursalTotal($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrMostrarSerie($item, $valor)
    {
        $tabla = "serie";

        $respuesta = ModeloSucursal::mdlMostrarSucursalSerie($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrSucursal()
    {
        $tabla = "sucursales";
        $item = 'id';
        $valor = isset($_SESSION['id_sucursal']) ? $_SESSION['id_sucursal'] : 1;
        $respuesta = ModeloSucursal::mdlMostrarSucursalTotal($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrSucursalConexion($item, $valor)
    {
        $tabla = "sucursales";
        $respuesta = ModeloSucursal::mdlMostrarSucursal($tabla, $item, $valor);
        return $respuesta;
    }


    public static function ctrCrearSucursal($datos)
    {

        $tabla = 'sucursales';

        $respuesta = ModeloSucursal::mdlCrearSucursal($tabla, $datos);


        if ($respuesta == 'ok') {
            return 'ok';
        } else {
            return 'error';
        }
    }
    public static function ctrCrearSerie($datos)
    {
        $id_sucursal = ModeloSucursal::mdlObtenerUltimaSucursalId();
        $idsucursal = $id_sucursal['id'];
        $tabla = 'serie';
        $guardarSeries = ModeloSucursal::mdlCrearSerieSucursal($tabla,  $idsucursal, $datos);
    }

    public static function ctrEditarSucursal($datos)
    {

        $tabla = 'sucursales';
        $valor = '0000';
        $respuesta = ModeloSucursal::mdlEditarSucursal($tabla, $valor, $datos);
    }

    public static function ctrEditarSucursales($datos)
    {

        $tabla = 'sucursales';

        $respuesta = ModeloSucursal::mdlEditarSucursales($tabla, $datos);
        return $respuesta;
    }

    public static function ctrActivarSucursal($datos)
    {
        $tabla = 'sucursales';
        $respuesta = ModeloSucursal::mdlActivarSucursales($tabla, $datos);
        return $respuesta;
    }
}
