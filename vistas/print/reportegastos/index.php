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
use Controladores\ControladorCaja;
use Controladores\ControladorUsuarios;
use Spipu\Html2Pdf\Html2Pdf;
$emisor = ControladorEmpresa::ctrEmisor();
$fechaini = $_POST['fechaInicial'];
$fechafin = $_POST['fechaFinal'];
$fechai = str_replace('/', '-', $fechaini);
$fechaInicial = date('Y-m-d', strtotime($fechai));

$fechaf = str_replace('/', '-', $fechafin);
$fechaFinal = date('Y-m-d', strtotime($fechaf));

$searchGastos = @$_POST['searchR'];
$aColumns = array('descripcion', "monto"); //Columnas de busqueda
$tabla1 = "gastos";
$tabla2 = "usuarios";
$sWhere = "";
if (isset($searchGastos) && !empty($searchGastos) && empty($fechafin)) {
    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . $searchGastos . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
if (empty($fechafin) && empty($searchGastos)) {
    $sWhere = "";
}

if (!empty($fechafin) && empty($searchGastos)) {

    $sWhere = "WHERE g.fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
}

if (!empty($searchGastos)  && !empty($fechafin)) {

    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= "g.fecha BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' AND " . $aColumns[$i] . " LIKE '%" . $searchGastos . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

$pdo =  Conexion::conectar();
$totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $tabla1 g INNER JOIN $tabla2 u ON g.id_usuario=u.id $sWhere");
$totalRegistros = $totalRegistros->fetch()['numrows'];

//main query to fetch the data
$pdo =  Conexion::conectar();

$registros = $pdo->prepare("SELECT g.*, g.fecha as fechag, u.* FROM $tabla1 g INNER JOIN $tabla2 u ON g.id_usuario = u.id $sWhere ORDER BY g.id DESC");


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
