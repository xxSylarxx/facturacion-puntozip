<?php

namespace Controladores;


use Modelos\ModeloInventarios;
use Conect\Conexion;
use Controladores\ControladorEmpresa;
// use Modelos\ModeloCategorias;
// use Modelos\ModeloProductos;

class ControladorInventarios
{


    public static function ctrNuevaEntrada($detalle, $comprobante, $id_sucursal)
    {

        $tabla = 'inventario';
        $respuesta = ModeloInventarios::mdlNuevaEntrada($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }

    public static function ctrNuevaSalida($detalle, $comprobante, $id_sucursal)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlNuevaSalida($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }
    public static function ctrNuevaSalidaGuia($detalle, $comprobante, $id_sucursal)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlNuevaSalidaGuia($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }

    public static function ctrNuevaDevolucionCompra($detalle, $comprobante, $id_sucursal)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlNuevaDevolucionCompra($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }
    public static function ctrNuevaDevolucionVenta($detalle, $comprobante, $id_sucursal)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlNuevaDevolucionVenta($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }
    
    public static function ctrNuevaDevolucionGuia($detalle, $comprobante, $id_sucursal)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlNuevaDevolucionGuia($tabla, $detalle, $comprobante, $id_sucursal);
        return $respuesta;
    }

    public static function ctrNuevoAjusteInventario($datos)
    {

        $tabla = 'inventario';
        $respuesta = ModeloInventarios::mdlNuevoAjusteInventario($tabla, $datos);
        return $respuesta;
    }


    public static function ctrMostrarInventarios($item, $valor)
    {

        $tabla = "inventario";
        $respuesta = ModeloInventarios::mdlMostrarInventarios($tabla, $item, $valor);
        return $respuesta;
    }
    // public static function ctrMostrarInventariosKardex($item, $valor)
    // {

    //     $tabla = "inventario";
    //     $respuesta = ModeloInventarios::mdlMostrarInventariosKardex($tabla, $item, $valor);
    //     return $respuesta;
    // }

    public  function ctrListarInventarios()
    {

        $respuesta = ModeloInventarios::mdlListarInventarios();
        echo $respuesta;
    }
    public  function ctrListarKardex()
    {

        $respuesta = ModeloInventarios::mdlListarKardex();
        echo $respuesta;
    }

    public static function ctrDescargaReporteInventarioExcel()
    {
        session_start();
        $fechaFinal = '';
        $fechaInicial = '';
        $searchInventario = '';

        $fechaInicial = $_GET['fechaInicial'];

        $fechaFinal = $_GET['fechaFinal'];

        $searchInventario = $_GET['searchInventarios'];
        $selectSucursal = $_GET['selectSucursal'];

        $emisor = ControladorEmpresa::ctrEmisor();

        $aColumns = array("descripcion", "codigo", "accion");
        $tabla1 = "inventario";
        $tabla2 = "productos";
        $tabla3 = "usuarios";
        $sWhere = "";

        if ($_SESSION['perfil'] == 'Administrador') {
            if (isset($selectSucursal) && !empty($selectSucursal)) {
                $id_sucursal = "i.id_sucursal =  $selectSucursal  AND";
            } else {
                $id_sucursal = '';
            }
        } else {
            $id_sucursal = "i.id_sucursal = " . $emisor['id'] . " AND";
        }

        if (isset($_POST['searchInventarios']) && !empty($_POST['searchInventarios']) && empty($_POST["fechaFinal"])) {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchInventario . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        if (empty($_POST["fechaFinal"]) && empty($_POST['searchInventarios'])) {

            $sWhere = "WHERE i.id_sucursal =  $selectSucursal";
        }
        if (empty($_POST["fechaFinal"]) && empty($_POST['searchInventarios']) && empty($_POST['selectSucursal'])) {

            $sWhere = "";
        }

        if (!empty($_POST["fechaFinal"]) && empty($_POST['searchInventarios'])) {

            $sWhere = "WHERE $id_sucursal  fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
        }

        if (isset($_POST['searchInventarios']) && !empty($_POST['searchInventarios'])  && !empty($_POST['fechaFinal'])) {

            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "$id_sucursal fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchInventario . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        $registros = Conexion::conectar()->prepare("SELECT i.id, i.id_sucursal, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ");


        $registros->execute();

        $registros = $registros->fetchall();

        $Name = $_GET["reporte"] . date('d-m-Y') . '.xls';

        header("Content-type: application/vnd.ms-excel; charset=iso-8859-1"); // Archivo de Excel    
        header('Content-Disposition: attachment; filename="' . $Name . '"');

        echo "<table> 
        <tr>
        <td colspan='5' style='text-align:center; font-weight:bold; font-size:28px; background: #42A5F5; padding:8px;'>INVENTARIO</td>
        </tr>";
        echo '<tr>
        <td  style="font-weight:bold;  text-align:center;">#</td>
        <td  style="font-weight:bold;  text-align:center;">PRODUCTO</td>
        <td  style="font-weight:bold;  text-align:center;">MOVIMIENTO</td>
        <td  style="font-weight:bold;  text-align:center;">FECHA</td>
        <td  style="font-weight:bold;  text-align:center;">CANTIDAD</td>
              
        </tr>';
        foreach ($registros as $k => $v) {
            echo '  <tr>
                <td>' . ++$k . '</td>
                <td>' . $v['descripcion'] . '</td>
                <td>' . $v['movimiento'] . '</td>
                <td>' . $v['fecha'] . '</td>
                <td>' . $v['cantidad'] . '</td>
                </tr>';
        }

        echo '</table>';
    }


    public static function ctrDescargaReporteKardexExcel()
    {
        $fechaFinal = '';
        $fechaInicial = '';
        $searchInventario = '';
        $fechaini = $_GET["fechaInicial"];
        $fechaini2 = str_replace('/', '-', $fechaini);
        $fechaInicial = date('Y-m-d', strtotime($fechaini2));

        $fechafin = $_GET["fechaFinal"];
        $fechafin2 = str_replace('/', '-', $fechafin);
        $fechaFinal = date('Y-m-d', strtotime($fechafin2));

        $searchkardex = $_GET['idproducto'];


        // $emisor = ControladorEmpresa::ctrEmisor();

        $aColumns = array("descripcion", "codigo", "accion");
        $tabla1 = "inventario";
        $tabla2 = "productos";
        $tabla3 = "usuarios";
        $sWhere = "";

        if (isset($_GET['idproducto']) && !empty($_GET['idproducto']) && empty($_GET["fechaFinal"])) {
            $sWhere = "WHERE p.id = '$searchkardex'";
        }
        if (empty($fechaFinal) && empty($searchkardex)) {
            $sWhere = "";
        }

        if (!empty($_GET["fechaFinal"]) && empty($_GET['idproducto'])) {

            $sWhere = "WHERE fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
        }

        if (isset($_GET['idproducto']) && !empty($_GET['idproducto'])  && !empty($_GET['fechaFinal'])) {

            $sWhere = "WHERE p.id = '$searchkardex' AND fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
        }

        $registros = Conexion::conectar()->prepare("SELECT i.id, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ORDER BY i.id DESC");


        $registros->execute();

        $registros = $registros->fetchall();

        $Name = $_GET["reporte"] . date('d-m-Y') . '.xls';

        header("Content-type: text/html; charset=iso-8859-1"); // Archivo de Excel    
        header("Content-type: application/vnd.ms-excel; charset=iso-8859-1"); // Archivo de Excel    
        header('Content-Disposition: attachment; filename="' . $Name . '"');

        echo "<table> ";
        echo '<thead>

        
        <tr>
            <td colspan="6"  style="text-align:center; font-weight:bold; font-size:28px; background: #3F51B5; padding:8px;">TARJETA KARDEX</td>
        </tr>';
        echo utf8_decode('<tr>
            <th colspan="2"  style="text-align:center; font-weight:bold; font-size:20px; background: #1E88E5; padding:8px;">FECHA Y Documento / Descripción Mvto.</th>
            <th colspan="4"  style="text-align:center; font-weight:bold; font-size:20px; background: #81C784; padding:8px;">UNIDADES</th>
        </tr>
        <tr>
            <th>FECHA</th>
            <th>MOVIMIENTO</th>
            <th>INV. INICIAL</th>
            <th>ENTRADA</th>
            <th>SALIDA</th>
            <th>INV. FINAL</th>
            <!-- <th>Acciones</th> -->
        </tr>
    </thead>');
        echo '<tbody>';
        foreach ($registros as $k => $v) {
            $entrada = 0;
            $salida = 0;

            $entrada = $v['accion'] == 'entrada' ? $v['cantidad'] : 0;
            $salida = $v['accion'] == 'salida' ? $v['cantidad'] : 0;


            $stok_actual = $v['accion'] == 'entrada' ? $v['stock_actual'] - $entrada : $v['stock_actual'] + $salida;

            echo utf8_decode('
         <tr>
         <td class="">' . date_format(date_create($v['fecha']), 'd/m/Y H:i:s') . '</td>
         <td class="">' . $v['movimiento'] . '</td>
         <td class="">' . $stok_actual . '</td>
         <td class="">' . $entrada . '</td>
         <td class="">' . $salida . '</td>
         <td class="">' . $v['stock_actual'] . '</td>
         </tr>
        
        ');
        }

        echo '</tbody>
        </table>';
    }
}
