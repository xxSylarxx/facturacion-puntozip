<?php

namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;
use Controladores\ControladorSucursal;

class ModeloProductos
{

    public static function mdlMostrarTodosProductos($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE  $item = :$item ORDER BY id DESC");
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
     public static function mdlMostrarProductos($tabla, $item, $valor, $idsucursal)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE id_sucursal = $idsucursal AND $item = :$item ORDER BY id DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE id_sucursal = $idsucursal");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }


        $stmt->close();
        $stmt = null;
    }
    public static function mdlMostrarProductosMultiAlmacen($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE  $item = :$item ORDER BY id DESC");
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
    public static function mdlMostrarProductosUnidades($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id DESC");
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
    public static function mdlMostrarProductosTotal($tabla, $item, $valor, $query)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $query $item = :$item ORDER BY id DESC");
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
    public static function mdlMostrarProductosMasVendidos($tabla, $item, $valor, $orden)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY $orden DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM  ORDER BY $orden DESC");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }


        $stmt->close();
        $stmt = null;
    }
    public static function mdlMostrarProductosStock($tabla, $item, $valor)
    {


        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id DESC");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch();


        $stmt->close();
        $stmt = null;
    }

    // REGISTRO DE PRODUCTOS
    public static function mdlCrearProducto($tabla, $datos)
    {
        $item = null;
        $valor = null;
        $respuesta = ControladorSucursal::ctrSucursalPrincipal($item, $valor);
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, id_sucursal, id_proveedor, codigo, serie, descripcion, imagen, stock, codigoafectacion, codunidad) VALUES (:id_categoria, :id_sucursal, :id_proveedor, :codigo, :serie, :descripcion, :imagen, :stock, :codigoafectacion, :codunidad)");

        if (isset($datos['id_sucursal']) && $datos['id_sucursal']  != 'todos') {
            $stmt->bindParam(":id_categoria", $datos['id_categoria'], PDO::PARAM_INT);
            $stmt->bindParam(":id_sucursal", $datos['id_sucursal'], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", $datos['id_proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
            $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
            $stmt->bindParam(":stock", $datos['stock'], PDO::PARAM_STR);
            /* $stmt->bindParam(":tipo_precio", $datos['tipo_precio'], PDO::PARAM_STR);
            $stmt->bindParam(":valor_unitario", $datos['valor_unitario'], PDO::PARAM_STR);
            $stmt->bindParam(":precio_pormayor", $datos['precio_pormayor'], PDO::PARAM_STR);
            $stmt->bindParam(":precio_unitario", $datos['precio_unitario'], PDO::PARAM_STR);
            $stmt->bindParam(":precio_compra", $datos['precio_compra'], PDO::PARAM_STR); 
            $stmt->bindParam(":igv", $datos['igv'], PDO::PARAM_STR); */
            $stmt->bindParam(":codigoafectacion", $datos['codigoafectacion'], PDO::PARAM_STR);
            $stmt->bindParam(":codunidad", $datos['unidad'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return   'ok';
            } else {
                return  'error';
            }

            $stmt = null;
        } else {

            foreach ($respuesta as $k => $value) {
                $stmt->bindParam(":id_categoria", $datos['id_categoria'], PDO::PARAM_INT);
                $stmt->bindParam(":id_sucursal", $value['id'], PDO::PARAM_INT);
                $stmt->bindParam(":id_proveedor", $datos['proveedor'], PDO::PARAM_INT);
                $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
                $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
                $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
                $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
                $stmt->bindParam(":stock", $datos['stock'], PDO::PARAM_STR);
                /* $stmt->bindParam(":tipo_precio", $datos['tipo_precio'], PDO::PARAM_STR);
                $stmt->bindParam(":valor_unitario", $datos['valor_unitario'], PDO::PARAM_STR);
                $stmt->bindParam(":precio_pormayor", $datos['precio_pormayor'], PDO::PARAM_STR);
                $stmt->bindParam(":precio_unitario", $datos['precio_unitario'], PDO::PARAM_STR);
                $stmt->bindParam(":precio_compra", $datos['precio_compra'], PDO::PARAM_STR);
                $stmt->bindParam(":igv", $datos['igv'], PDO::PARAM_STR); */
                $stmt->bindParam(":codigoafectacion", $datos['codigoafectacion'], PDO::PARAM_STR);
                $stmt->bindParam(":codunidad", $datos['unidad'], PDO::PARAM_STR);

                $stmt->execute();
            }
            return 'ok';
        }
    }
    // EDITAR PRODUCTO
    public static function mdlEditarProducto($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set id = :id, id_categoria = :id_categoria, id_proveedor = :id_proveedor, id_sucursal=:id_sucursal, codigo=:codigo, serie = :serie, descripcion = :descripcion, imagen = :imagen, stock = :stock, codigoafectacion = :codigoafectacion, codunidad = :codunidad WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":id_categoria", $datos['id_categoria'], PDO::PARAM_INT);
        $stmt->bindParam(":id_sucursal", $datos['id_sucursal'], PDO::PARAM_INT);
        $stmt->bindParam(":id_proveedor", $datos['id_proveedor'], PDO::PARAM_INT);
        $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(":stock", $datos['stock'], PDO::PARAM_STR);
        $stmt->bindParam(":codigoafectacion", $datos['codigoafectacion'], PDO::PARAM_STR);
        $stmt->bindParam(":codunidad", $datos['unidad'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }
    // EDITAR PRODUCTO
    public static function mdlActivaDesactivaUnidadMedida($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set activo = :activo WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $datos['modo'], PDO::PARAM_STR);


        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }
    public static function mdlActivaDesactivaProducto($tabla, $datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set activo = :activo WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $datos['modo'], PDO::PARAM_STR);


        $stmt->execute();
    }
    // EDITAR PRODUCTO
    public static function mdlActualizarStock($tabla, $detalle, $valor)
    {

        if ($valor == null) {
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla set stock = :stock WHERE id = :id");

            foreach ($detalle as $k => $v) {
                if (!empty($v['id'])) {
                    $tabla = 'productos';
                    $item = 'id';
                    $valor = $v['id'];
                    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);

                    // foreach ($productos as $i => $prod){

                    $cantidad = $productos['stock'] - $v['cantidad'];

                    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":stock", $cantidad, PDO::PARAM_STR);


                    $stmt->execute();
                }
            }
        } else {

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla set stock = :stock WHERE id = :id");

            foreach ($detalle as $k => $v) {
                if (!empty($v['id'])) {
                    $tabla = 'productos';
                    $item = 'id';
                    $valor = $v['id'];
                    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);

                    // foreach ($productos as $i => $prod){

                    $cantidad = $productos['stock'] + $v['cantidad'];

                    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":stock", $cantidad, PDO::PARAM_STR);


                    $stmt->execute();
                }
            }
        }
    }

    // EDITAR PRODUCTO MAS VENDIDO
    public static function mdlActualizarMasVendido($tabla, $detalle, $valor)
    {

        if ($valor == null) {
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla set ventas = :ventas WHERE id = :id");

            foreach ($detalle as $k => $v) {
                $tabla = 'productos';
                $item = 'id';
                $valor = $v['id'];
                $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);



                $cantidad = $productos['ventas'] - $v['cantidad'];

                $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
                $stmt->bindParam(":ventas", $cantidad, PDO::PARAM_STR);


                $stmt->execute();
            }
        } else {

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla set ventas = :ventas WHERE id = :id");

            foreach ($detalle as $k => $v) {
                $tabla = 'productos';
                $item = 'id';
                $valor = $v['id'];
                $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);

                // foreach ($productos as $i => $prod){

                $cantidad = $productos['ventas'] + $v['cantidad'];

                $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
                $stmt->bindParam(":ventas", $cantidad, PDO::PARAM_STR);


                $stmt->execute();
                //  }
            }
        }
    }

    // ELIMINAR PRODUCTO
    public static function mdlEliminarProducto($tabla, $datos)
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



    // LISTAR PRODUCTOS OTRO MÉTODO
    public static function mdlListarProductos()
    {

        $content =  "<tbody class='body-productos'></tbody>";
        return $content;
    }
    // LISTAR PRODUCTOS PARA LAS VENTAS
    public static function mdlListarProductosVentas()
    {

        $content =  "<tbody class='body-productos-ventas'></tbody>";
        return $content;
    }
    // LISTAR PRODUCTOS PARA LAS VENTAS
    public static function mdlListarProductosGuia()
    {

        $content =  "<tbody class='body-productos-guia'></tbody>";
        return $content;
    }



    public static function mdlActualizarStockPorAjuste($datos)
    {

        $tabla = 'productos';
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set stock = :stock WHERE id = :id");


        $item = 'id';
        $valor = $datos['idproducto'];
        $producto = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);

        if ($datos['accion'] == 'salida') {
            $cantidad = $producto['stock'] - $datos['cantidad'];
        } else {
            $cantidad = $producto['stock'] + $datos['cantidad'];
        }


        $stmt->bindParam(":id", $datos['idproducto'], PDO::PARAM_INT);
        $stmt->bindParam(":stock", $cantidad, PDO::PARAM_STR);


        $stmt->execute();
    }

    public static function mdlCambiarFoto($tabla, $datos)
    {
        // ACTUALIZAR BIENES Y SERVICIOS SELVA, EMPRESA============

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET imagen=:imagen WHERE id=:id");
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":imagen", $datos['foto'], PDO::PARAM_STR);


        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }

        // OBTENER EL ULTIMO ID PRODUCTO
        public static function mdlObtenerUltimoProductoId()
        {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM productos ORDER BY id DESC LIMIT 1");
    
            $stmt->execute();
    
            return $stmt->fetch();
        }
        public static function mdlCrearSeries($tabla, $datosSeries, $idProducto){
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_producto, serie) VALUES (:id_producto, :serie) ");
            foreach($datosSeries as $value){
         if(!empty($value) && strlen($value) >= 3){
            $stmt->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
            $stmt->bindParam(":serie", $value, PDO::PARAM_STR);
        
           $stmt->execute();
               
        }else{
                    return 'error';
                }
        }
        }
    
        public static function mdlMostrarSeriesProductos($tabla, $item, $valor)
        {
    
            if ($item != null) {
    
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE  $item = :$item ORDER BY id DESC");
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
        public static function mdlMostrarSeriesProductosActualizar($tabla, $item, $valor)
        {
    
            if ($item != null) {
    
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE  $item = :$item ORDER BY id DESC");
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
    
                $stmt->execute();
                return $stmt->fetchAll();
            }    
            $stmt->close();
            $stmt = null;
        }
        public static function mdlMostrarSeriesProductosGuias($tabla, $item, $valor)
        {
    
            if ($item != null) {
    
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE  $item = :$item AND disponible = 's' ORDER BY id DESC");
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
    
                $stmt->execute();
                return $stmt->fetchAll();
            }
    
            $stmt->close();
            $stmt = null;
        }

         // EDITAR PRODUCTO
    public static function mdlActualizarSerie($tabla, $idSerie, $item, $valor)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set $item = :$item WHERE id = :id");

        $stmt->bindParam(":id", $idSerie, PDO::PARAM_INT);
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);


        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }

     // ELIMINAR PRODUCTO
     public static function mdlEliminarSerie($tabla, $idSerie)
     {
 
         $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id=:id");
         $stmt->bindParam(":id", $idSerie, PDO::PARAM_INT);
 
         if ($stmt->execute()) {
             return 'ok';
         } else {
             return 'error';
         }
         $stmt->close();
         $stmt = null;
     }
}
