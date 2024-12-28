<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloGastos
{

    public static function mdlMostrarGastos($tabla, $item, $valor)
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



    public static function mdlCrearGasto($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(monto, descripcion, fecha, apertura, id_usuario) VALUES (:monto, :descripcion, :fecha, :apertura, :id_usuario) ");
        $stmt->bindParam(":monto", $datos['monto'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos['fecha'], PDO::PARAM_STR);
        $stmt->bindParam(":apertura", $datos['apertura'], PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario", $datos['id_usuario'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    // EDITAR CATEGORIA
    public static function mdlEditarGastos($tabla, $datos)
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
    public static function mdlEliminarGasto($tabla, $valor)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id=:id");
        $stmt->bindParam(":id", $valor, PDO::PARAM_INT);

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

    public static function mdlListarGastos()
    {

        $content =  "<tbody class='body-gastos'></tbody>";
        return $content;
    }
}
