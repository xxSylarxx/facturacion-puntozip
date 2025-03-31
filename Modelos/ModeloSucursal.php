<?php

namespace Modelos;

use Conect\Conexion;
use Controladores\ControladorEmpresa;
use PDO;

class ModeloSucursal
{
    // MOSTRAR EMISOR
    public static function mdlMostrarSucursal($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item AND activo='s'");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla   WHERE  activo='s'");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }


        $stmt->close();
        $stmt = null;
    }

    public static function mdlMostrarSucursalTotal($tabla, $item, $valor)
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
    // MOSTRAR EMISOR
    public static function mdlMostrarSucursalSerie($tabla, $item, $valor)
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



    public static function mdlCrearSucursal($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_empresa, codigo, nombre_sucursal, direccion, pais, departamento, provincia, distrito, ubigeo, telefono, correo) VALUES (:id_empresa, :codigo, :nombre_sucursal, :direccion, :pais, :departamento, :provincia, :distrito, :ubigeo, :telefono, :correo)");
        $stmt->bindParam(":id_empresa", $datos['id_empresa'], PDO::PARAM_INT);
        $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
        $stmt->bindParam(":nombre_sucursal", $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":pais", $datos['pais'], PDO::PARAM_STR);
        $stmt->bindParam(":departamento", $datos['departamento'], PDO::PARAM_STR);
        $stmt->bindParam(":provincia", $datos['provincia'], PDO::PARAM_STR);
        $stmt->bindParam(":distrito", $datos['distrito'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos['correo'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
    public static function mdlCrearSerieSucursal($tabla, $id_sucursal, $datos)
    {

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id,id_sucursal, tipocomp, serie, correlativo) VALUES (null,:id_sucursal, :tipocomp, :serie, :correlativo)");
        $tipocomp = $datos['tipocomp'];
        $correlativo = $datos['ccorrelativo'];
        foreach ($datos['serie'] as $k => $value) {
            $tipocompp = $tipocomp[$k];
            $correlativop = $correlativo[$k];
            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(":tipocomp", $tipocompp, PDO::PARAM_STR);
            $stmt->bindParam(":serie", $value, PDO::PARAM_STR);
            $stmt->bindParam(":correlativo", $correlativop, PDO::PARAM_INT);

            $stmt->execute();
        }
        return 'ok';
    }


    // EDITAR SUCURSAL
    public static function mdlEditarSucursal($tabla, $valor, $datos)
    {

        $stmt = Conexion::conectar();
        $stmt = $stmt->prepare("UPDATE $tabla SET direccion=:direccion, ubigeo=:ubigeo, departamento=:departamento, provincia=:provincia, distrito=:distrito, telefono=:telefono, correo=:correo WHERE codigo = :codigo");

        $stmt->bindParam(":codigo", $valor, PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":departamento", $datos['departamento'], PDO::PARAM_STR);
        $stmt->bindParam(":provincia", $datos['provincia'], PDO::PARAM_STR);
        $stmt->bindParam(":distrito", $datos['distrito'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos['correo_ventas'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }

        $stmt->close();
        $stmt = null;
    }
    // EDITAR SUCURSAL
    public static function mdlEditarSucursales($tabla, $datos)
    {

        $stmt = Conexion::conectar();
        $stmt = $stmt->prepare("UPDATE $tabla SET codigo=:codigo, nombre_sucursal=:nombre_sucursal, direccion=:direccion, ubigeo=:ubigeo, departamento=:departamento, provincia=:provincia, distrito=:distrito, telefono=:telefono, correo=:correo WHERE id = :id");

        $stmt->bindParam(":id", $datos['eidsucursal'], PDO::PARAM_INT);
        $stmt->bindParam(":codigo", $datos['ecodigo'], PDO::PARAM_STR);
        $stmt->bindParam(":nombre_sucursal", $datos['enombre'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['edireccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['eubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":departamento", $datos['edepartamento'], PDO::PARAM_STR);
        $stmt->bindParam(":provincia", $datos['eprovincia'], PDO::PARAM_STR);
        $stmt->bindParam(":distrito", $datos['edistrito'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['etelefono'], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos['ecorreo'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }

        $stmt->close();
        $stmt = null;
    }


    public static function mdlActivarSucursales($tabla, $datos)
    {

        $stmt = Conexion::conectar();
        $stmt = $stmt->prepare("UPDATE $tabla SET activo=:activo WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $datos['activo'], PDO::PARAM_STR);


        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }

        $stmt->close();
        $stmt = null;
    }
    // OBTENER EL ULTIMO ID COMPROBANTE
    public static function mdlObtenerUltimaSucursalId()
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM sucursales ORDER BY id DESC LIMIT 1");

        $stmt->execute();

        return $stmt->fetch();
    }
}
