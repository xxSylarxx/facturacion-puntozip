<?php
session_start();
require_once(dirname(__FILE__) . "/../../../pdf/vendor/autoload.php");

//clases de acceso a datos
require_once("../../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorInventarios;
// use Controladores\ControladorProductos;
// use Controladores\ControladorReportes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
// use Controladores\ControladorSunat;
// use Controladores\ControladorUsuarios;
use Spipu\Html2Pdf\Html2Pdf;
// require_once "../../Controladores/cantidad_en_letras.php";
$fechaFinal = '';
$fechaInicial = '';
$searchInventario = '';
$fechaini = $_POST["fechaInicial"];
$fechaini2 = str_replace('/', '-', $fechaini);
$fechaInicial = date('Y-m-d', strtotime($fechaini2));
$selectSucursal = $_POST['idkSucursal'];
$fechafin = $_POST["fechaFinal"];
$fechafin2 = str_replace('/', '-', $fechafin);
$fechaFinal = date('Y-m-d', strtotime($fechafin2));

@$searchkardex = $_POST['idproducto'];


$emisor = ControladorEmpresa::ctrEmisor();
$sucursal = ControladorSucursal::ctrSucursal();
$aColumns = array("descripcion", "codigo", "accion");
$tabla1 = "inventario";
$tabla2 = "productos";
$tabla3 = "usuarios";
$sWhere = "";

if ($_SESSION['perfil'] == 'Administrador') {
    if (isset($selectSucursal) && !empty($selectSucursal)) {
        $id_sucursal = "i.id_sucursal =  $selectSucursal  AND";
        $id_sucursal = "i.id_sucursal =  $selectSucursal  AND";
        $item = "id";
        $valor = $selectSucursal;
        $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
    } else {
        $id_sucursal = '';
    }
} else {
    $id_sucursal = "i.id_sucursal = " . $sucursal['id'] . " AND";
}
if (isset($searchkardex) && !empty($searchkardex) && empty($fechafin)) {

    $sWhere = "WHERE $id_sucursal  p.id = '$searchkardex'";
}

if (isset($searchkardex) && !empty($searchkardex) && !empty($fechafin)) {

    $sWhere = "WHERE $id_sucursal p.id = '$searchkardex' AND fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
}
if (empty($fechafin) && empty($searchkardex)) {
    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $id_sucursal . ' ' . $aColumns[$i] . " LIKE '%" . $searchkardex . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

if (!empty($fechafin) && empty($searchkardex)) {

    $sWhere = "WHERE $id_sucursal fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
}


$totalRegistros   = Conexion::conectar()->query("SELECT count(*) AS numrows FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto=p.id $sWhere");
$totalRegistros = $totalRegistros->fetch()['numrows'];

$registros = Conexion::conectar()->prepare("SELECT i.id, i.id_sucursal, i.id_producto, i.id_usuario, i.movimiento, i.accion,i.cantidad,  i.stock_actual, i.fecha, p.descripcion, p.ventas, p.codigo, p.id FROM $tabla1 i INNER JOIN $tabla2 p ON i.id_producto = p.id $sWhere ORDER BY i.id DESC");


$registros->execute();

$registros = $registros->fetchall();


ob_start();

require_once("report.php");

$html = ob_get_clean();
$html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);


$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output('reporte' . '.pdf', 'I');
