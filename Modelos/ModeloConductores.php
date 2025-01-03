<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloConductores
{

    public static $tabla = 'conductor';

    public static function mdlMostrarConductores($item, $valor)
    {
        $tabla = self::$tabla;
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY apellidos");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlGuardarConductor($datos)
    {
        $tabla = self::$tabla;
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (nombres, apellidos, tipdoc, numdoc, numplaca, numbrevete, marca_vehiculo) 
            VALUES (:nombres, :apellidos, :tipdoc, :numdoc, :numplaca, :numbrevete, :marca_vehiculo)");
        $stmt->bindParam(":nombres", $datos['nombres'], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datos['apellidos'], PDO::PARAM_STR);
        $stmt->bindParam(":tipdoc", $datos['tipdoc'], PDO::PARAM_STR);
        $stmt->bindParam(":numdoc", $datos['numdoc'], PDO::PARAM_STR);
        $stmt->bindParam(":numplaca", $datos['numplaca'], PDO::PARAM_STR);
        $stmt->bindParam(":numbrevete", $datos['numbrevete'], PDO::PARAM_STR);
        $stmt->bindParam(":marca_vehiculo", $datos['marca_vehiculo'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
    public static function mdlEditarConductor($datos)
    {
        $tabla = self::$tabla;
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombres = :nombres, apellidos = :apellidos, tipdoc = :tipdoc, numdoc = :numdoc, numplaca = :numplaca, numbrevete = :numbrevete, marca_vehiculo = :marca_vehiculo WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":nombres", $datos['nombres'], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datos['apellidos'], PDO::PARAM_STR);
        $stmt->bindParam(":tipdoc", $datos['tipdoc'], PDO::PARAM_STR);
        $stmt->bindParam(":numdoc", $datos['numdoc'], PDO::PARAM_STR);
        $stmt->bindParam(":numplaca", $datos['numplaca'], PDO::PARAM_STR);
        $stmt->bindParam(":numbrevete", $datos['numbrevete'], PDO::PARAM_STR);
        $stmt->bindParam(":marca_vehiculo", $datos['marca_vehiculo'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlBuscarConductor($valor)
    {
        $tabla = self::$tabla;
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE nombre LIKE :valor OR documento LIKE :valor OR ruc LIKE :valor");
        $parametros = array(':valor' => '%' . $valor . '%');
        $stmt->execute($parametros);
        return $stmt->fetchall();
    }

    public static function mdlEliminarConductor($datos)
    {
        $tabla = self::$tabla;
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
}
