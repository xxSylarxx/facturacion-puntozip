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

$fechafin = $_POST["fechaFinal"];
$fechafin2 = str_replace('/', '-', $fechafin);
$fechaFinal = date('Y-m-d', strtotime($fechafin2));


$emisor = ControladorEmpresa::ctrEmisor();

$searchArqueoCajas = @$_GET['searchR'];
// $selectnum = $_GET['selectnum'];
$aColumns = array('descripcion', "codigo", "accion"); //Columnas de busqueda
$tabla1 = "arqueo_cajas";
$tabla2 = "cajas";
$tabla3 = "usuarios";
$sWhere = "";

if (!empty($fechafin) && empty($searchArqueoCajas)) {

    $sWhere = "WHERE fecha_apertura BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
}

if (!empty($searchArqueoCajas)  && !empty($fechafin)) {

    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= "fecha_apertura BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchArqueoCajas . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

$totalRegistros   = Conexion::conectar()->query("SELECT count(*) AS numrows FROM $tabla1 a INNER JOIN $tabla2 c ON a.id_caja=c.id $sWhere");
$totalRegistros = $totalRegistros->fetch()['numrows'];

$registros = Conexion::conectar()->prepare("SELECT a.*, c.* FROM $tabla1 a INNER JOIN $tabla2 c ON a.id_caja = c.id $sWhere ORDER BY a.id DESC");


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
