<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloCaja
{

    public static function mdlMostrarCajas($tabla, $item, $valor)
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
    public static function mdlMostrarArqueoCajas($tabla, $item, $valor)
    {
        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item AND estado = 1");
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


    public static function mdlCrearCaja($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(numero_caja, nombre) VALUES (:numero_caja, :nombre) ");
        $stmt->bindParam(":numero_caja", $datos['numero_caja'], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos['nombre'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    // EDITAR CATEGORIA
    public static function mdlEditarCaja($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET numero_caja = :numero_caja, nombre = :nombre, activo=:activo, fecha_modifica=:fecha_modifica WHERE id = :id ");
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":numero_caja", $datos['numero_caja'], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(":activo", $datos['activo'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_modifica", $datos['fecha_modifica'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    // ELIMINAR CATEGORIA
    public static function mdlEliminarCaja($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id=:id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    //ARQUEO DE CAJAS=====================================================

    public static function mdlAperturaCaja($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_caja, id_usuario, monto_inicial, fecha_apertura) VALUES (:id_caja, :id_usuario, :monto_inicial, :fecha_apertura) ");
        $stmt->bindParam(":id_caja", $datos['id_caja'], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos['id_usuario'], PDO::PARAM_INT);
        $stmt->bindParam(":monto_inicial", $datos['monto_inicial'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_apertura", $datos['fecha_apertura'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlListarArqueoCajas()
    {

        $content =  "<tbody class='body-arqueocajas'></tbody>";
        return $content;
    }

    public static function mdlTotalVentas($tabla, $where)
    {
        $stmt = Conexion::conectar()->prepare("SELECT sum(total) as total   FROM $tabla  $where");

        $stmt->execute();
        return $stmt->fetch();
    }
    public static function mdlTotalCompras($tabla, $where)
    {
        $stmt = Conexion::conectar()->prepare("SELECT sum(total) as total   FROM $tabla  $where");

        $stmt->execute();
        return $stmt->fetch();
    }
    public static function mdlTotalNotas($tabla, $where)
    {
        $stmt = Conexion::conectar()->prepare("SELECT sum(total) as total   FROM $tabla  $where");

        $stmt->execute();
        return $stmt->fetch();
    }
    public static function mdlTotalGastos($tabla, $where)
    {
        $stmt = Conexion::conectar()->prepare("SELECT sum(monto) as total   FROM $tabla  $where");

        $stmt->execute();
        return $stmt->fetch();
    }

    public static function mdlCierreCaja($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fecha_cierre = :fecha_cierre, monto_final = :monto_final, total_ventas=:total_ventas, egresos=:egresos, gastos=:gastos, estado=:estado WHERE id = :id ");
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_cierre", $datos['fecha_cierre'], PDO::PARAM_STR);
        $stmt->bindParam(":monto_final", $datos['monto_final'], PDO::PARAM_STR);
        $stmt->bindParam(":total_ventas", $datos['total_ventas'], PDO::PARAM_INT);
        $stmt->bindParam(":egresos", $datos['egresos'], PDO::PARAM_STR);
        $stmt->bindParam(":gastos", $datos['gastos'], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos['estado'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }


    public static function mdlTotalRegistrosVentas($tabla, $where)
    {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*)  FROM $tabla  $where");

        $stmt->execute();
        return $stmt->fetch();
    }


    public static function mdlActualizarVentaCierre($tabla,$id_usuario)
    {

        $id = $id_usuario;
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET `apertura` = '0' WHERE codvendedor = $id");
        $stmt->execute();
    }

    public static function mdlActualizarNotasCierre($tabla,$id_usuario)
    {

        $id = $id_usuario;
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET `apertura` = '0' WHERE id_usuario = $id");
        $stmt->execute();
    }
    public static function mdlActualizarGastoCierre($tabla,$id_usuario)
    {

        $id = $id_usuario;
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET `apertura` = '0' WHERE id_usuario = $id");
        $stmt->execute();
    }
}
