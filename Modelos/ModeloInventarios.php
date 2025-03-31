<?php

namespace Modelos;

use Conect\Conexion;
use Controladores\ControladorProductos;
use PDO;

class ModeloInventarios
{

    public static function mdlNuevaEntrada($tabla, $detalle, $comprobante, $id_sucursal)
    {
        $movimiento = 'Compra ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'entrada';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {
            if (!empty($v['id'])) {
                $item = 'id';
                $valor = $v['id'];
                $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
                $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
                $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
                $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
                $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
                $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
                $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
                $stmt->bindParam(":id_producto", $v['id'], PDO::PARAM_INT);
                $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

                $stmt->execute();
                // }
            }
        }
    }
    public static function mdlNuevaSalida($tabla, $detalle, $comprobante, $id_sucursal)

    {
        $movimiento = 'Venta ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'salida';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {

            $item = 'id';
            $valor = $v['id'];
            $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
            // foreach ($productos as $i => $prod) {
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $v['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

            $stmt->execute();
            // }
        }
    }

    public static function mdlNuevaSalidaGuia($tabla, $detalle, $comprobante, $id_sucursal)

    {
        $movimiento = 'Guía ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'salida';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {

            $item = 'id';
            $valor = $v['id'];
            $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
            // foreach ($productos as $i => $prod) {
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $v['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

            $stmt->execute();
            // }
        }
    }


    public static function mdlNuevaDevolucionCompra($tabla, $detalle, $comprobante, $id_sucursal)

    {
        $movimiento = 'Devolución Compra ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'salida';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {

            $item = 'id';
            $valor = $v['id'];
            $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
            // foreach ($productos as $i => $prod) {
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $v['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

            $stmt->execute();
            // }
        }
    }
    public static function mdlNuevaDevolucionVenta($tabla, $detalle, $comprobante, $id_sucursal)

    {
        $movimiento = 'Devolución Venta ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'entrada';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {

            $item = 'id';
            $valor = $v['id'];
            $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
            // foreach ($productos as $i => $pvrod) {
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $v['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

            $stmt->execute();
            // }
        }
    }

    public static function mdlNuevaDevolucionGuia($tabla, $detalle, $comprobante, $id_sucursal)

    {
        $movimiento = 'Retorno Guía ' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $accion = 'entrada';
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal,movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal,:movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");
        foreach ($detalle as $k => $v) {

            $item = 'id';
            $valor = $v['id_producto'];
            $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);
            // foreach ($productos as $i => $pvrod) {
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $v['id_producto'], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $comprobante['codvendedor'], PDO::PARAM_INT);

            $stmt->execute();
            // }
        }
    }

    public static function mdlNuevoAjusteInventario($tabla, $datos)

    {
        $movimiento = 'Ajuste de inventario: ' . $datos['accion'];
        $accion = $datos['accion'];
        $fecha = date("Y-m-d H:i:s");
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_sucursal, movimiento, accion, cantidad, stock_actual, fecha, id_producto, id_usuario) VALUES (:id_sucursal, :movimiento, :accion, :cantidad, :stock_actual, :fecha, :id_producto, :id_usuario) ");

        $item = 'id';
        $valor = $datos['idproducto'];
        $productos = ControladorProductos::ctrMostrarProductosStock($item, $valor);

        $stmt->bindParam(":id_sucursal", $datos['id_sucursal'], PDO::PARAM_INT);
        $stmt->bindParam(":movimiento", $movimiento, PDO::PARAM_STR);
        $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
        $stmt->bindParam(":cantidad", $datos['cantidad'], PDO::PARAM_INT);
        $stmt->bindParam(":stock_actual", $productos['stock'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->bindParam(":id_producto", $productos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos['id_usuario'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlMostrarInventarios($tabla, $item, $valor)
    {
        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
        $stmt->close();
        $stmt = null;
    }
    // public static function mdlMostrarInventariosKardex($tabla1, $tabla2, $sWhere)
    // {
    //     $registros = Conexion::conectar()->prepare("SELECT i.id, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ORDER BY i.id DESC");



    //     $registros->execute();

    //     $registros = $registros->fetchall();
    // }

    // LISTAR INVENTARIOS
    public static function mdlListarInventarios()
    {

        $content =  "<tbody class='body-inventarios'></tbody>";
        return $content;
    }

    public static function mdlListarKardex()
    {

        $content =  "<tbody class='body-kardex'></tbody>";
        return $content;
    }
}
