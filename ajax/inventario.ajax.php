<?php
session_start();

require_once "../vendor/autoload.php";

use Controladores\ControladorInventarios;
use Controladores\ControladorProductos;
use Controladores\ControladorClientes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\Controlador;

class AjaxInventario
{

    public function ajaxNuevoAjuste()
    {

        if (isset($_POST['cantidad']) && !empty($_POST['cantidad'] && $_POST['cantidad'] <> 0)) {
            $idproducto = $_POST['idproducto'];
            if ($_POST['cantidad'] > 0) {
                $motivo = 'entrada';
                $cantidad = $_POST['cantidad'];
            } else {
                $motivo = 'salida';
                $cantidad = abs($_POST['cantidad']);
            }
            $datos = array(
                'idproducto' => $idproducto,
                'cantidad' => $cantidad,
                'accion' => $motivo,
                'id_usuario' => $_SESSION['id'],
                'id_sucursal' => $_SESSION['id_sucursal'],
            );
            // var_dump($datos);
            $respProducto = ControladorProductos::ctrActualizarStockPorAjuste($datos);

            $respuesta = ControladorInventarios::ctrNuevoAjusteInventario($datos);
            echo $respuesta;
        } else {
            echo "error";
        }
    }
    public function ajaxLLenarProductos()
    {

        $item = null;
        $valor = null;
        $idsucursal = $_POST['id_sucursal'];
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);
        // var_dump($respuesta);

        echo '<option value="">BUSCAR EL PRODUCTO</option>';
        foreach ($respuesta as $k => $v) {
            echo '<option value="' . $v['id'] . '">' . $v['descripcion'] . '</option>
                                                        ';
        }
    }
}
if (isset($_POST['idproducto'])) {
    $objproducto = new AjaxInventario();
    $objproducto->ajaxNuevoAjuste();
}
if (isset($_POST['id_sucursal'])) {
    $objproducto = new AjaxInventario();
    $objproducto->ajaxLLenarProductos();
}
