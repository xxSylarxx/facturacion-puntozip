<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloCuentasBanco
{

    // MOSTRAR PROVEEDORES
    public static function mdlMostrarCuentasBanco($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchall();
        }


        $stmt->close();
        $stmt = null;
    }

    public static function mdlGuardarCuentasBanco($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(moneda, tipocuenta, nombrebanco, titular, numerocuenta, numerocci,  descripcion) VALUES (:moneda, :tipocuenta, :nombrebanco, :titular, :numerocuenta, :numerocci, :descripcion)");

        $stmt->bindParam(":moneda", $datos['moneda'], PDO::PARAM_STR);
        $stmt->bindParam(":tipocuenta", $datos['tipocuenta'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrebanco", $datos['nombrebanco'], PDO::PARAM_STR);
        $stmt->bindParam(":titular", $datos['titular'], PDO::PARAM_STR);
        $stmt->bindParam(":numerocuenta", $datos['numerocuenta'], PDO::PARAM_STR);
        $stmt->bindParam(":numerocci", $datos['numerocci'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
    public static function mdlEditarCuentasBanco($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET moneda=:moneda, tipocuenta=:tipocuenta, nombrebanco=:nombrebanco, titular=:titular, numerocuenta=:numerocuenta, numerocci=:numerocci, descripcion=:descripcion WHERE id=:id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":moneda", $datos['moneda'], PDO::PARAM_STR);
        $stmt->bindParam(":tipocuenta", $datos['tipocuenta'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrebanco", $datos['nombrebanco'], PDO::PARAM_STR);
        $stmt->bindParam(":titular", $datos['titular'], PDO::PARAM_STR);
        $stmt->bindParam(":numerocuenta", $datos['numerocuenta'], PDO::PARAM_STR);
        $stmt->bindParam(":numerocci", $datos['numerocci'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

}
