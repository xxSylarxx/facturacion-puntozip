<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloConductores
{

    public static $tabla = 'conductores';

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
        if (strlen($datos['docIdentidad']) > 8) {
            $ruc = $datos['docIdentidad'];
            $razon_social = $datos['razon_social'];
            $nombre = '';
        } else {
            $ruc = '';
        }
        if (strlen($datos['docIdentidad']) == 8) {
            $dni = $datos['docIdentidad'];
            $razon_social = '';
            $nombre = $datos['razon_social'];
        } else {
            $dni = '';
        }

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (nombres, apellidos, documento, ruc, celular, num_placa, num_brevete, marca_vehiculo) 
            VALUES (:nombres, :apellidos, :documento, :ruc, :celular, :num_placa, :num_brevete, :marca_vehiculo)");

        $stmt->bindParam(":nombres", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(":documento", $dni, PDO::PARAM_STR);
        $stmt->bindParam(":ruc", $ruc, PDO::PARAM_STR);
        $stmt->bindParam(":celular", $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(":num_placa", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":num_brevete", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":marca_vehiculo", $datos['ubigeo'], PDO::PARAM_STR);

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
        if (strlen($datos['docIdentidad']) > 8) {
            $ruc = $datos['docIdentidad'];
            $razon_social = $datos['razon_social'];
            $nombre = '';
        } else {
            $ruc = '';
        }
        if (strlen($datos['docIdentidad']) == 8) {
            $dni = $datos['docIdentidad'];
            $razon_social = '';
            $nombre = $datos['razon_social'];
        } else {
            $dni = '';
        }

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre=:nombre, documento=:documento, ruc=:ruc, razon_social=:razon_social, email=:email, telefono=:telefono, direccion=:direccion, ubigeo=:ubigeo WHERE id=:id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":documento", $dni, PDO::PARAM_STR);
        $stmt->bindParam(":ruc", $ruc, PDO::PARAM_STR);
        $stmt->bindParam(":razon_social", $razon_social, PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['ubigeo'], PDO::PARAM_STR);
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
}
