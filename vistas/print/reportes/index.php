<?php
session_start();
require_once(dirname(__FILE__) . "/../../../pdf/vendor/autoload.php");

//clases de acceso a datos
require_once("../../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorReportes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;
use Spipu\Html2Pdf\Html2Pdf;
// require_once "../../Controladores/cantidad_en_letras.php";
if (isset($_POST['fechaInicial'])) {
    $tipocomp = $_POST['tipocomp'];
    $fechaini = $_POST["fechaInicial"];
    $fechaini2 = str_replace('/', '-', $fechaini);
    $fechaInicial = date('Y-m-d', strtotime($fechaini2));
    $fechafin = $_POST["fechaFinal"];
    $fechafin2 = str_replace('/', '-', $fechafin);
    $fechaFinal = date('Y-m-d', strtotime($fechafin2));
    $selectSucursal = $_POST['selectSucursal'];
    $emisor = ControladorEmpresa::ctrEmisor();
    $sucursal = ControladorSucursal::ctrSucursal();
    if ($_SESSION['perfil'] == 'Administrador') {
        if (isset($selectSucursal) && !empty($selectSucursal)) {
            $id_sucursal = "id_sucursal =  $selectSucursal  AND";
        } else {
            $id_sucursal = '';
        }
    } else {
        $id_sucursal = "id_sucursal = " . $sucursal['id'] . " AND";
    }

    if ($tipocomp == '01' || $tipocomp == '03' || $tipocomp == '02' || $tipocomp == '00') {
        $tabla = 'venta';
    }
    if ($tipocomp == '07') {
        $tabla = 'nota_credito';
    }
    if ($tipocomp == '08') {
        $tabla = 'nota_debito';
    }

    if (isset($selectSucursal) && !empty($selectSucursal)) {
        $item = "id";
        $valor = $selectSucursal;
        $sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);
    } else {
        $sucursal = $sucursal;
    }

    $resultado = ControladorReportes::ctrReporteVentasPDF($tabla, $fechaInicial, $fechaFinal, $tipocomp, $id_sucursal);
    // var_dump($resultado);


    ob_start();

    require_once("report.php");

    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('L', 'a4', 'fr', true, 'UTF-8', 0);

    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setTestTdInOnePage(true);
    $html2pdf->writeHTML($html);
    header('Content-type: application/pdf');

    $carpeta = dirname(__FILE__) . '/../../img/usuarios/' . $_SESSION['usuario'] . '/pdf';
    $files = glob(dirname(__FILE__) . '/../../img/usuarios/' . $_SESSION['usuario'] . '/pdf/*'); //obtenemos todos los nombres de los ficheros
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file); //elimino el fichero
    }

    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0755, true);
    }
    $nombrexml = 'reporte';

    $doc = $html2pdf->output(dirname(__FILE__) . '/../../img/usuarios/' . $_SESSION['usuario'] . '/pdf/' . $nombrexml . '.pdf', 'F');

    $rand = rand(22, 99999);
    echo '<iframe 
title="PDF" 
src="vistas/print/viewpdf/public/pdfjs-2.5.207-es5-dist/web/viewer.html?file=../../../../../img/usuarios/' . $_SESSION['usuario'] . '/pdf/' . $nombrexml . '.pdf?n=' . $rand . '"
width="100%"
height="650px">
  
</iframe>';
}
