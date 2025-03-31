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
use Controladores\ControladorCaja;
use Controladores\ControladorUsuarios;
use Spipu\Html2Pdf\Html2Pdf;

$idArqueo = $_POST['idArqueo'];

$emisor = ControladorEmpresa::ctrEmisor();


$item = 'id';
$valor = $idArqueo;
$arqueoCaja = ControladorCaja::ctrMostrarArqueoCajas($item, $valor);
$item = 'id';



$valor = $arqueoCaja['id_caja'];
$caja = ControladorCaja::ctrMostrarCajas($item, $valor);
$item = 'id';
$valor = $arqueoCaja['id_usuario'];
$usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
ob_start();

require_once("report.php");

$html = ob_get_clean();
$html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);


$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output('reporte' . '.pdf', 'I');
