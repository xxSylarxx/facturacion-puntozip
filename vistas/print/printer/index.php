<?php
session_start();
require_once(dirname(__FILE__) . "/../../../pdf/vendor/autoload.php");
require_once(dirname(__FILE__) . "/../../../Controladores/cantidad_en_letras.php");
//clases de acceso a datos
require_once(dirname(__FILE__) . "/../../../vendor/autoload.php");

use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSucursal;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;
use Controladores\ControladorCuentasBanco;
use Spipu\Html2Pdf\Html2Pdf;


if (!empty($_REQUEST['a4'])) {
    $tipoPrint = $_REQUEST['a4'];
}
if (!empty($_REQUEST['tk'])) {
    $tipoPrint = $_REQUEST['tk'];
}

$empresa = ControladorEmpresa::ctrEmisor();

$item = "id";
$valor = $_REQUEST["idCo"];
$venta = ControladorVentas::ctrMostrarVentas($item, $valor);

if (!empty($venta['xmlbase64'])) {
    $ruta_archivo_cdr = __DIR__ . "/../../../api/cdr/";
    $xml_decode = file_get_contents($ruta_archivo_cdr . $venta['xmlbase64']) or die("Error: Cannot create object");
    // Obteniendo datos del archivo .XML
    $DOM = new \DOMDocument('1.0', 'UTF-8');
    $DOM->loadXML($xml_decode);

    $hash_cdr = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;
} else {
    $hash_cdr = '';
}


$item = 'idenvio';
$valor = $venta['idbaja'];
$ticket = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

$item = "id";
$valor = $venta['id_sucursal'];
$sucursal = ControladorSucursal::ctrMostrarSucursalTotal($item, $valor);

$item = "id";
$valor = $venta['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor = ControladorEmpresa::ctrEmisor();

$item = "idventa";
$valor = $venta['id'];
$detalle = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);

$item = "id_venta";
$valor = $venta['id'];
$ventaCredito = ControladorVentas::ctrMostrarVentasCredito($item, $valor);

$valor = $venta['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

$item = "codigo";
$valor = $venta['metodopago'];
$metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item, $valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = 'id';
$valor = $_SESSION['id'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);


$item = 'id';
$valor = $venta['id_cuenta'];
$cuentaBanco = ControladorCuentasBanco::ctrMostrarCuentasBanco($item, $valor);



ob_start();
if ($tipoPrint == 'A4') {
    require_once("../invoiceA4.php");
    $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);
}
if ($tipoPrint == 'TK') {
    require_once("../invoice-ticket.php");
    $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', array(77.5, 300), 'fr', true, 'UTF-8', 0);
}
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output($nombrexml . '.pdf', 'I');
